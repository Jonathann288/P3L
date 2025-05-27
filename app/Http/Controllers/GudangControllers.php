<?php

namespace App\Http\Controllers;
use \App\Models\Pegawai;
use \App\Models\Penitip;
use \App\Models\transaksipenitipan;
use App\Models\Barang;
use App\Models\kategoribarang;
use App\Models\detailtransaksipenitipan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
        $query = TransaksiPenitipan::with('penitip', 'pegawai');  // Gunakan nama class yang benar

        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('tanggal_penitipan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_akhir_penitipan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_batas_pengambilan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_pengambilan_barang', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('penitip', function ($penitipQuery) use ($searchTerm) {
                        $penitipQuery->where('nama_penitip', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('email_penitip', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('nomor_ktp', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('nomor_telepon_penitip', 'LIKE', "%{$searchTerm}%");
                    })
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
        $kategoris = KategoriBarang::select('id_kategori', 'nama_kategori', 'nama_sub_kategori')  // Perbaiki nama class
            ->orderBy('nama_kategori')
            ->get();

            

        return view('gudang.DashboardTitipanBarang', compact('titipans', 'penitips', 'kategoris'));
    }

    public function storeTitipanBarang(Request $request)
    {
        // Debug: Check if files are being received
        \Log::info('Files received:', ['files' => $request->hasFile('foto_barang'), 'all_files' => $request->allFiles()]);

        // Improved validation with better error messages
        $validated = $request->validate([
            'id_penitip' => 'required|exists:penitip,id_penitip',
            'id_kategori' => 'required|exists:kategoribarang,id_kategori',
            'tanggal_penitipan' => 'required|date',
            'nama_barang' => 'required|string|max:255',
            'deskripsi_barang' => 'required|string|min:3',
            'harga_barang' => 'required|numeric|min:1',
            'berat_barang' => 'required|numeric|min:0.1',
            'status_barang' => 'required|string|in:tersedia,pending,terjual,rusak',
            'foto_barang' => 'required|array|min:1|max:5',
            'foto_barang.*' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'has_garansi' => 'required|in:ya,tidak',
            'garansi_type' => 'nullable|string|in:6_bulan,1_tahun,2_tahun,custom',
            'garansi_barang' => 'nullable|date|after:tanggal_penitipan',
        ], [
            'id_penitip.required' => 'Penitip harus dipilih',
            'id_penitip.exists' => 'Penitip tidak ditemukan',
            'id_kategori.required' => 'Kategori barang harus dipilih',
            'foto_barang.required' => 'Minimal 1 foto barang harus diupload',
            'foto_barang.array' => 'Foto barang harus berupa array file',
            'foto_barang.min' => 'Minimal upload 1 foto',
            'foto_barang.max' => 'Maksimal upload 5 foto',
            'foto_barang.*.required' => 'Setiap file foto harus valid',
            'foto_barang.*.file' => 'Setiap item harus berupa file',
            'foto_barang.*.image' => 'File harus berupa gambar',
            'foto_barang.*.mimes' => 'File harus berformat: jpeg, png, jpg, gif, atau webp',
            'foto_barang.*.max' => 'Ukuran file maksimal 2MB',
            'has_garansi.required' => 'Pilihan garansi harus dipilih',
            'garansi_barang.after' => 'Tanggal garansi harus setelah tanggal penitipan',
        ]);

        try {
            DB::beginTransaction();

            // Check authenticated staff
            $pegawai = Auth::guard('pegawai')->user();
            if (!$pegawai) {
                throw new \Exception('Pegawai tidak terautentikasi');
            }

            // Upload photos first with better error handling
            $fotoPaths = [];
            if ($request->hasFile('foto_barang')) {
                foreach ($request->file('foto_barang') as $index => $foto) {
                    if ($foto && $foto->isValid()) {
                        // Create unique filename
                        $filename = 'barang_' . time() . '_' . $index . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();

                        try {
                            $path = $foto->storeAs('foto_barang', $filename, 'public');
                            if ($path) {
                                $fotoPaths[] = $path;
                                \Log::info("Photo uploaded successfully: {$path}");
                            } else {
                                throw new \Exception("Failed to store photo {$index}");
                            }
                        } catch (\Exception $e) {
                            \Log::error("Error uploading photo {$index}: " . $e->getMessage());
                            throw new \Exception("Gagal mengupload foto ke-" . ($index + 1));
                        }
                    } else {
                        \Log::error("Invalid file at index {$index}");
                        throw new \Exception("File foto ke-" . ($index + 1) . " tidak valid");
                    }
                }
            }

            if (empty($fotoPaths)) {
                throw new \Exception('Tidak ada foto yang berhasil diupload');
            }

            \Log::info('All photos uploaded successfully:', $fotoPaths);

            // Calculate dates
            $tanggalPenitipan = Carbon::parse($validated['tanggal_penitipan']);
            $tanggalAkhirPenitipan = $tanggalPenitipan->copy()->addDays(30);
            $tanggalBatasPengambilan = $tanggalAkhirPenitipan->copy()->addDays(7);


            // Calculate garansi_barang
            $garansiBarang = null;
            if ($validated['has_garansi'] === 'ya') {
                if ($request->filled('garansi_barang')) {
                    // Jika tanggal garansi sudah diisi manual
                    $garansiBarang = Carbon::parse($validated['garansi_barang']);
                } else {
                    // Hitung otomatis berdasarkan garansi_type atau default 1 tahun
                    $garansiType = $validated['garansi_type'] ?? '1_tahun';
                    $garansiBarang = $tanggalPenitipan->copy();

                    switch ($garansiType) {
                        case '6_bulan':
                            $garansiBarang->addMonths(6);
                            break;
                        case '2_tahun':
                            $garansiBarang->addYears(2);
                            break;
                        case '1_tahun':
                        default:
                            $garansiBarang->addYear();
                            break;
                    }
                }
            }

            // Save item
            $barang = new Barang();
            $barang->id = $this->generateBarangId();
            $barang->id_kategori = $validated['id_kategori'];
            $barang->nama_barang = $validated['nama_barang'];
            $barang->deskripsi_barang = $validated['deskripsi_barang'];
            $barang->harga_barang = $validated['harga_barang'];
            $barang->berat_barang = $validated['berat_barang'];
            $barang->status_barang = $validated['status_barang'];
            $barang->masa_penitipan = 30;
            $barang->foto_barang = $fotoPaths[0]; // main photo
            $barang->rating_barang = 0;
            $barang->garansi_barang = $garansiBarang;

            if (!$barang->save()) {
                throw new \Exception('Gagal menyimpan data barang');
            }

            \Log::info('Item saved successfully:', [
                'id_barang' => $barang->id_barang,
                'garansi_barang' => $garansiBarang ? $garansiBarang->format('Y-m-d H:i:s') : 'No warranty'
            ]);

            // Save consignment transaction
            $titipan = new TransaksiPenitipan();
            $titipan->id = $this->generateCustomId();
            $titipan->id_pegawai = $pegawai->id_pegawai;
            $titipan->id_penitip = $validated['id_penitip'];
            $titipan->tanggal_penitipan = $tanggalPenitipan;
            $titipan->tanggal_akhir_penitipan = $tanggalAkhirPenitipan;
            $titipan->tanggal_batas_pengambilan = $tanggalBatasPengambilan;
            $titipan->tanggal_pengambilan_barang = null;
            $titipan->foto_barang = $fotoPaths; // all photos

            if (!$titipan->save()) {
                throw new \Exception('Gagal menyimpan data transaksi penitipan');
            }

            \Log::info('Consignment transaction saved successfully:', ['id' => $titipan->id_transaksi_penitipan]);

            // Save transaction details
            $detailTransaksi = new DetailTransaksiPenitipan();
            $detailTransaksi->id_transaksi_penitipan = $titipan->id_transaksi_penitipan;
            $detailTransaksi->id_barang = $barang->id_barang;

            if (!$detailTransaksi->save()) {
                throw new \Exception('Gagal menyimpan detail transaksi');
            }

            DB::commit();

            return redirect()->route('gudang.DashboardTitipanBarang')
                ->with('success', 'Transaksi penitipan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();

            // Clean up uploaded photos on error
            if (!empty($fotoPaths)) {
                foreach ($fotoPaths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                        \Log::info("Cleaned up photo: {$path}");
                    }
                }
            }

            \Log::error('Error in storeTitipanBarang:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'input' => $request->except(['foto_barang']), // Don't log file data
                'files' => $request->hasFile('foto_barang') ? 'Files present' : 'No files'
            ]);

            return redirect()->back()
                ->withInput($request->except(['foto_barang'])) // Don't return file input
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function validateFileUpload(Request $request)
    {
        // Check if files exist
        if (!$request->hasFile('foto_barang')) {
            throw new \Exception('Tidak ada file yang diupload');
        }

        $files = $request->file('foto_barang');

        // Ensure it's an array
        if (!is_array($files)) {
            $files = [$files];
        }

        // Check each file
        foreach ($files as $index => $file) {
            if (!$file) {
                throw new \Exception("File ke-" . ($index + 1) . " kosong");
            }

            if (!$file->isValid()) {
                throw new \Exception("File ke-" . ($index + 1) . " tidak valid: " . $file->getErrorMessage());
            }

            // Check file type
            $allowedMimes = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, $allowedMimes)) {
                throw new \Exception("File ke-" . ($index + 1) . " harus berformat: " . implode(', ', $allowedMimes));
            }

            // Check file size (2MB = 2048KB)
            if ($file->getSize() > 2048 * 1024) {
                throw new \Exception("File ke-" . ($index + 1) . " terlalu besar. Maksimal 2MB");
            }

            // Check if it's actually an image
            $imageInfo = @getimagesize($file->getPathname());
            if (!$imageInfo) {
                throw new \Exception("File ke-" . ($index + 1) . " bukan gambar yang valid");
            }
        }

        return $files;
    }

    private function generateCustomId()
    {
        $last = TransaksiPenitipan::orderBy('id', 'desc')->first();  // Gunakan nama class yang benar

        if (!$last) {
            return 'T0001';
        }

        // Extract nomor dari ID terakhir
        $lastNumber = (int) substr($last->id, 1);
        $nextNumber = $lastNumber + 1;

        return 'T' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    private function generateBarangId()
    {
        $last = Barang::orderBy('id', 'desc')->first();

        if (!$last) {
            return 'B0001';
        }

        $lastNumber = (int) substr($last->id, 1);
        $nextNumber = $lastNumber + 1;

        return 'B' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function createTitipanBarang()
    {
        $penitips = Penitip::select('id_penitip', 'nama_penitip', 'email_penitip', 'nomor_ktp')
            ->orderBy('nama_penitip')
            ->get();

        $titipans = TransaksiPenitipan::with('penitip', 'pegawai')  // Gunakan nama class yang benar
            ->orderBy('tanggal_penitipan', 'desc')
            ->get();

        $kategoris = KategoriBarang::select('id_kategori', 'nama_kategori', 'nama_sub_kategori')  // Gunakan nama class yang benar
            ->orderBy('nama_kategori')
            ->get();

        return view('gudang.DashboardTitipanBarang', compact('titipans', 'penitips', 'kategoris'));
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

    public function showDaftarBarang()
{
    // Ambil data pegawai yang sedang login (sesuaikan dengan sistem autentikasi Anda)
    $pegawai = auth()->user(); // atau sesuai dengan cara Anda mengambil data pegawai
    
    // Atau jika menggunakan session/cara lain:
    // $pegawai = Pegawai::find(session('id_pegawai'));
    
    // Ambil semua barang dengan relasi yang diperlukan
    $barang = Barang::with([
        'kategoribarang',
        'detailTransaksiPenitipan.transaksiPenitipan.pegawai',
        'detailTransaksiPenitipan.transaksiPenitipan.penitip'
    ])->get();
    
    return view('gudang.DaftarBarang', compact('pegawai', 'barang'));
}

// Alternatif jika Anda ingin menampilkan barang yang ditangani oleh pegawai tertentu
public function showDaftarBarangByPegawai()
{
    // Ambil data pegawai yang sedang login
    $pegawai = auth()->user(); // sesuaikan dengan sistem autentikasi Anda
    
    // Ambil barang yang ditangani oleh pegawai ini
    $barang = Barang::whereHas('detailTransaksiPenitipan.transaksiPenitipan', function($query) use ($pegawai) {
        $query->where('id_pegawai', $pegawai->id_pegawai);
    })->with([
        'kategoribarang',
        'detailTransaksiPenitipan.transaksiPenitipan.pegawai',
        'detailTransaksiPenitipan.transaksiPenitipan.penitip'
    ])->get();
    
    return view('gudang.DaftarBarang', compact('pegawai', 'barang'));
}
}