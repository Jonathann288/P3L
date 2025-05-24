<?php

namespace App\Http\Controllers;
use \App\Models\Pegawai;
use \App\Models\Penitip;
use \App\Models\transaksipenitipan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class GudangControllers extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();
        return response()->json($pegawai);
    }

    public function showDashboardGudang()
    {
        $pegawai = Auth::guard('pegawai')->user();

        if (!$pegawai) {
            return redirect()->route('gudang.DashboardGudang')->with('error', 'Silakan login terlebih dahulu');
        }

        return view('gudang.DashboardGudang', compact('pegawai'));
    }

    public function showTitipanBarang(Request $request)
    {
        $query = transaksipenitipan::with('penitip', 'pegawai');

        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                // Search dalam tabel transaksipenitipan
                $q->where('tanggal_penitipan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_akhir_penitipan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_batas_pengambilan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_pengambilan_barang', 'LIKE', "%{$searchTerm}%")
                    // Search dalam tabel penitip
                    ->orWhereHas('penitip', function ($penitipQuery) use ($searchTerm) {
                        $penitipQuery->where('nama_penitip', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('email_penitip', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('nomor_ktp', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('nomor_telepon_penitip', 'LIKE', "%{$searchTerm}%");
                    })
                    // Search dalam tabel pegawai
                    ->orWhereHas('pegawai', function ($pegawaiQuery) use ($searchTerm) {
                        $pegawaiQuery->where('nama_pegawai', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('email_pegawai', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        // Handle filter by status
        if ($request->has('status') && !empty($request->status)) {
            $status = $request->status;

            if ($status === 'diambil') {
                $query->whereNotNull('tanggal_pengambilan_barang');
            } elseif ($status === 'terlambat') {
                $query->whereNull('tanggal_pengambilan_barang')
                    ->where('tanggal_batas_pengambilan', '<', now());
            } elseif ($status === 'dalam_penitipan') {
                $query->whereNull('tanggal_pengambilan_barang')
                    ->where(function ($q) {
                        $q->whereNull('tanggal_batas_pengambilan')
                            ->orWhere('tanggal_batas_pengambilan', '>=', now());
                    });
            }
        }

        $titipans = $query->orderBy('tanggal_penitipan', 'desc')->get();
        $penitips = Penitip::select('id_penitip', 'nama_penitip', 'email_penitip', 'nomor_ktp')
            ->orderBy('nama_penitip')
            ->get();

        return view('gudang.DashboardTitipanBarang', compact('titipans', 'penitips'));

    }

    /**
     * Tambah transaksi penitipan barang baru
     */
    public function storeTitipanBarang(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'id_penitip' => 'required|exists:penitip,id_penitip',
            'tanggal_penitipan' => 'required|date',
            'foto_barang.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Ambil petugas QC yang sedang login
            $pegawai = Auth::guard('pegawai')->user();

            if (!$pegawai) {
                return redirect()->back()->with('error', 'Silakan login terlebih dahulu');
            }

            // Parse tanggal penitipan
            $tanggalPenitipan = Carbon::parse($validated['tanggal_penitipan']);

            // Hitung masa penitipan otomatis (30 hari dari tanggal masuk gudang)
            // Asumsi: tanggal masuk gudang = tanggal penitipan
            $tanggalMasukGudang = $tanggalPenitipan;
            $tanggalAkhirPenitipan = $tanggalMasukGudang->copy()->addDays(30);

            // Tanggal batas pengambilan = tanggal akhir penitipan + 7 hari grace period
            $tanggalBatasPengambilan = $tanggalAkhirPenitipan->copy()->addDays(7);

            // Handle upload foto barang
            $fotoPaths = [];
            if ($request->hasFile('foto_barang')) {
                foreach ($request->file('foto_barang') as $foto) {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $path = $foto->storeAs('foto_barang', $filename, 'public');
                    $fotoPaths[] = $path;
                }
            }

            // Buat transaksi penitipan baru
            $titipan = new transaksipenitipan();
            $titipan->id = $this->generateCustomId();
            $titipan->id_pegawai = $pegawai->id_pegawai;
            $titipan->id_penitip = $validated['id_penitip'];
            $titipan->tanggal_penitipan = $tanggalPenitipan;
            $titipan->tanggal_akhir_penitipan = $tanggalAkhirPenitipan;
            $titipan->tanggal_batas_pengambilan = $tanggalBatasPengambilan;
            $titipan->tanggal_pengambilan_barang = null;
            $titipan->foto_barang = $fotoPaths;
            $titipan->save();


            return redirect()->route('gudang.DashboardTitipanBarang')->with(
                'success',
                'Transaksi penitipan berhasil ditambahkan. Masa penitipan: ' .
                $tanggalPenitipan->format('d M Y') . ' - ' . $tanggalAkhirPenitipan->format('d M Y')
            );

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    private function generateCustomId()
    {
        $last = \App\Models\transaksipenitipan::orderBy('id', 'desc')->first();

        if (!$last || !preg_match('/^T\d+$/', $last->id)) {
            return 'T0001';
        }

        $lastNumber = (int) substr($last->id, 1);
        $nextNumber = $lastNumber + 1;

        return 'T' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Show form tambah transaksi
     */
    public function createTitipanBarang()
    {
        $penitips = Penitip::select('id_penitip', 'nama_penitip', 'email_penitip', 'nomor_ktp')
            ->orderBy('nama_penitip')
            ->get();

        return view('gudang.DashboardTitipanBarang', compact('titipans', 'penitips'));

    }

    /**
     * Hitung durasi penitipan otomatis
     */
    public function hitungDurasiPenitipan($tanggalMasukGudang)
    {
        $tanggalMasuk = Carbon::parse($tanggalMasukGudang);
        $tanggalAkhir = $tanggalMasuk->copy()->addDays(30);
        $tanggalBatasPengambilan = $tanggalAkhir->copy()->addDays(7);

        return [
            'tanggal_penitipan' => $tanggalMasuk,
            'tanggal_akhir_penitipan' => $tanggalAkhir,
            'tanggal_batas_pengambilan' => $tanggalBatasPengambilan,
            'durasi_hari' => 30,
            'grace_period_hari' => 7
        ];
    }

    /**
     * API endpoint untuk mendapatkan durasi penitipan
     */
    public function getDurasiPenitipan(Request $request)
    {
        $tanggalMasuk = $request->input('tanggal_masuk');

        if (!$tanggalMasuk) {
            return response()->json(['error' => 'Tanggal masuk gudang diperlukan'], 400);
        }

        try {
            $durasi = $this->hitungDurasiPenitipan($tanggalMasuk);

            return response()->json([
                'success' => true,
                'data' => [
                    'tanggal_penitipan' => $durasi['tanggal_penitipan']->format('Y-m-d'),
                    'tanggal_akhir_penitipan' => $durasi['tanggal_akhir_penitipan']->format('Y-m-d'),
                    'tanggal_batas_pengambilan' => $durasi['tanggal_batas_pengambilan']->format('Y-m-d'),
                    'durasi_hari' => $durasi['durasi_hari'],
                    'grace_period_hari' => $durasi['grace_period_hari'],
                    'info' => "Masa penitipan: {$durasi['durasi_hari']} hari + {$durasi['grace_period_hari']} hari grace period"
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Format tanggal tidak valid'], 400);
        }
    }

    /**
     * Pencarian transaksi dengan berbagai kriteria
     */
    public function searchTitipan(Request $request)
    {
        $query = transaksipenitipan::with('penitip', 'pegawai');

        // Search berdasarkan berbagai field
        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;

            $query->where(function ($q) use ($searchTerm) {
                // Search di tabel transaksipenitipan
                $q->where('id_transaksi_penitipan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_penitipan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_akhir_penitipan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_batas_pengambilan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_pengambilan_barang', 'LIKE', "%{$searchTerm}%")
                    // Search di tabel penitip
                    ->orWhereHas('penitip', function ($penitipQuery) use ($searchTerm) {
                        $penitipQuery->where('nama_penitip', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('email_penitip', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('nomor_ktp', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('nomor_telepon_penitip', 'LIKE', "%{$searchTerm}%");
                    })
                    // Search di tabel pegawai
                    ->orWhereHas('pegawai', function ($pegawaiQuery) use ($searchTerm) {
                        $pegawaiQuery->where('nama_pegawai', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('email_pegawai', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_penitipan', [
                $request->tanggal_dari,
                $request->tanggal_sampai
            ]);
        }

        // Filter berdasarkan penitip
        if ($request->filled('id_penitip')) {
            $query->where('id_penitip', $request->id_penitip);
        }

        // Filter berdasarkan pegawai QC
        if ($request->filled('id_pegawai')) {
            $query->where('id_pegawai', $request->id_pegawai);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $status = $request->status;

            switch ($status) {
                case 'tersedia':
                    $query->whereNull('tanggal_pengambilan_barang')
                        ->whereDate('tanggal_penitipan', '<=', now())
                        ->whereDate('tanggal_akhir_penitipan', '>=', now())
                        ->whereDoesntHave('detailTransaksi.barang.transaksiPenjualan')
                        ->whereDoesntHave('detailTransaksi.barang.donasi');
                    break;

                case 'terjual':
                    $query->whereHas('detailTransaksi.barang.transaksiPenjualan');
                    break;

                case 'barang_untuk_donasi':
                    $query->whereDate('tanggal_akhir_penitipan', '<', now())
                        ->whereDoesntHave('detailTransaksi.barang.donasi')
                        ->whereNull('tanggal_pengambilan_barang');
                    break;

                case 'didonasikan':
                    $query->whereHas('detailTransaksi.barang.donasi', function ($q) {
                        $q->whereNotNull('id_request');
                    });
                    break;

                case 'siap_diambil_kembali':
                    // Implementasi ini tergantung apakah Anda punya field seperti `status_pengambilan` atau `konfirmasi_pengambilan`
                    $query->where('status_pengambilan', 'siap_diambil'); // contoh
                    break;

                case 'diambil_kembali':
                    $query->whereNotNull('tanggal_pengambilan_barang');
                    break;
            }
        }


        $results = $query->orderBy('tanggal_penitipan', 'desc')->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $results,
                'count' => $results->count(),
                'message' => "Ditemukan {$results->count()} transaksi"
            ]);
        }
        $penitips = Penitip::select('id_penitip', 'nama_penitip', 'email_penitip', 'nomor_ktp')
            ->orderBy('nama_penitip')
            ->get();

        return view('gudang.DashboardTitipanBarang', [
            'titipans' => $results,
            'searchTerm' => $request->search_term,
            'penitips' => $penitips
        ]);
    }

    public function updateTitipanBarang(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'email_penitip' => 'required|email|max:255',
            'tanggal_penitipan' => 'required|date',
            'tanggal_akhir_penitipan' => 'nullable|date',
            'tanggal_batas_pengambilan' => 'nullable|date',
            'tanggal_pengambilan_barang' => 'nullable|date',
            'foto_barang.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'auto_calculate' => 'nullable|boolean'
        ]);

        try {
            // Ambil data transaksi
            $titipan = transaksipenitipan::findOrFail($id);

            // Update data penitip terkait
            $penitip = $titipan->penitip;
            if ($penitip) {
                $penitip->nama_penitip = $validated['nama_penitip'];
                $penitip->email_penitip = $validated['email_penitip'];
                $penitip->save();
            }

            // Update data titipan
            $titipan->tanggal_penitipan = $validated['tanggal_penitipan'];

            // Jika auto calculate diaktifkan atau tanggal masuk gudang berubah
            if (
                $request->auto_calculate ||
                ($validated['tanggal_penitipan'] && $validated['tanggal_penitipan'] != $titipan->tanggal_penitipan)
            ) {

                $tanggalMasuk = $validated['tanggal_penitipan'] ?? $validated['tanggal_penitipan'];
                $durasi = $this->hitungDurasiPenitipan($tanggalMasuk);

                $titipan->tanggal_penitipan = $durasi['tanggal_penitipan'];
                $titipan->tanggal_akhir_penitipan = $durasi['tanggal_akhir_penitipan'];
                $titipan->tanggal_batas_pengambilan = $durasi['tanggal_batas_pengambilan'];
            } else {
                // Manual input
                $titipan->tanggal_penitipan = $validated['tanggal_penitipan'] ?? $titipan->tanggal_penitipan;
                $titipan->tanggal_akhir_penitipan = $validated['tanggal_akhir_penitipan'];
                $titipan->tanggal_batas_pengambilan = $validated['tanggal_batas_pengambilan'];
            }

            $titipan->tanggal_pengambilan_barang = $validated['tanggal_pengambilan_barang'];

            // Handle foto barang - SELALU hapus foto lama saat ada update
            if ($request->hasFile('foto_barang')) {
                // Hapus semua foto lama
                $this->deleteOldPhotos($titipan);

                // Upload foto baru
                $fotoPaths = [];
                foreach ($request->file('foto_barang') as $foto) {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $path = $foto->storeAs('foto_barang', $filename, 'public');
                    $fotoPaths[] = $path;
                }

                // Set foto_barang sebagai array, akan otomatis di-convert ke JSON oleh mutator
                $titipan->foto_barang = $fotoPaths;
            }

            $titipan->save();

            return redirect()->route('gudang.DashboardTitipanBarang')->with('success', 'Data titipan berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function deleteOldPhotos($titipan)
    {
        $oldFotos = $titipan->foto_barang;

        if (empty($oldFotos)) {
            return;
        }

        // Pastikan $oldFotos adalah array
        if (is_string($oldFotos)) {
            $oldFotos = json_decode($oldFotos, true);
        }

        if (!is_array($oldFotos)) {
            return;
        }

        // Hapus setiap file foto lama
        foreach ($oldFotos as $oldFoto) {
            if (!empty($oldFoto)) {
                $cleanPath = trim($oldFoto);

                // Cek dan hapus file dari storage
                if (Storage::disk('public')->exists($cleanPath)) {
                    Storage::disk('public')->delete($cleanPath);
                }
            }
        }
    }

    public function deleteTitipanBarang($id)
    {
        try {
            $titipan = transaksipenitipan::findOrFail($id);

            // Hapus foto-foto terkait
            $this->deleteOldPhotos($titipan);

            // Hapus record dari database
            $titipan->delete();

            return redirect()->route('gudang.DashboardTitipanBarang')->with('success', 'Data titipan berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cetakNota($id)
    {
        try {
            // Ambil data transaksi dengan relasi
            $titipan = transaksipenitipan::with(['penitip', 'pegawai'])->findOrFail($id);

            // Data untuk PDF
            $data = [
                'titipan' => $titipan,
                'tanggal_cetak' => Carbon::now()->format('d F Y H:i:s'),
                'perusahaan' => [
                    'nama' => 'Gudang Titipan Barang',
                    'alamat' => 'Jl. Contoh No. 123, Kota ABC',
                    'telepon' => '(021) 1234-5678',
                    'email' => 'info@gudangtitipan.com'
                ]
            ];

            // Generate PDF dengan orientasi portrait dan ukuran A4
            $pdf = PDF::loadView('gudang.CetakNota', $data)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true
                ]);


            // Nama file
            $namaFile = 'Nota_Penitipan_' . $titipan->id . '_' . Carbon::now()->format('YmdHis') . '.pdf';

            // Return sebagai download
            return $pdf->download($namaFile);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencetak nota: ' . $e->getMessage());
        }
    }
}