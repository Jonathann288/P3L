<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\RequestDonasi;
use App\Models\Barang;
use App\Models\Transaksipenitipan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class DonasiControllers extends Controller
{

    public function submitDonasi(Request $request)
    {
        try {
            \Log::info('⏩ Masuk ke submitDonasi');

            // Validasi input
            $validated = $request->validate([
                'id_request' => 'required|exists:requestdonasi,id_request',
                'id_barang' => 'required|exists:barang,id_barang',
                'tanggal_donasi' => 'required|date',
                'nama_penerima' => 'required|string',
            ]);

            \Log::info('✅ Validasi Berhasil', $validated);

            // Update status request menjadi 'approved'
            $donasiRequest = RequestDonasi::find($request->id_request);
            if (!$donasiRequest) {
                \Log::error('❌ Request tidak ditemukan');
                return redirect()->back()->with('error', 'Request tidak ditemukan.');
            }

            $donasiRequest->status_request = 'approved';
            $donasiRequest->save();
            \Log::info('✅ Status request diupdate jadi approved');

            // Simpan data ke tabel donasi
            $donasi = Donasi::create([
                'id_barang' => $request->id_barang,
                'id_request' => $request->id_request,
                'nama_penerima' => $request->nama_penerima,
                'tanggal_donasi' => $request->tanggal_donasi,
            ]);

            \Log::info('✅ Data Donasi berhasil disimpan', $donasi->toArray());
             $barang = barang::find($request->id_barang);
            if ($barang) {
                $barang->status_barang = 'Sudah diDonasikan'; // atau 'tersedia', 'terpakai', dll sesuai logika kamu
                $barang->save();
                \Log::info('✅ Status barang diupdate jadi didonasikan');
            } else {
                \Log::warning('⚠️ Barang tidak ditemukan saat update status_barang');
            }

            return redirect()->route('owner.DashboardDonasi')->with('success', 'Donasi berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error('❌ Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function rejectDonasi(Request $request)
    {
        $id_request = $request->input('id_request');

        $requestDonasi = RequestDonasi::find($id_request);
        if (!$requestDonasi) {
            return redirect()->back()->with('error', 'Request donasi tidak ditemukan.');
        }

        $requestDonasi->status_request = 'Tolak'; // ubah status menjadi Tolak
        $requestDonasi->save();

        return redirect()->route('owner.DashboardDonasi')->with('success', 'Request donasi berhasil ditolak.');
    }


    public function historyDonasi()
    {
        $pegawaiLogin = Auth::guard('pegawai')->user();

        // Ambil data Donasi yang sudah di-approve
        $donasiApproved = Donasi::with(['barang', 'requestdonasi.organisasi'])
            ->get()
            ->map(function ($donasi) {
                return [
                    'id_request' => $donasi->id_request,
                    'nama_organisasi' => $donasi->requestdonasi->organisasi->nama_organisasi,
                    'tanggal_donasi' => $donasi->tanggal_donasi,
                    'deskripsi_request' => $donasi->requestdonasi->deskripsi_request,
                    'status_request' => $donasi->requestdonasi->status_request,
                    'nama_barang' => $donasi->barang->nama_barang,
                    'nama_penerima' => $donasi->nama_penerima,
                ];
            });

        // Ambil data Request Donasi yang berstatus Tolak
        $donasiRejected = RequestDonasi::with('organisasi')
            ->where('status_request', 'Tolak')
            ->get()
            ->map(function ($request) {
                return [
                    'nama_organisasi' => $request->organisasi->nama_organisasi,
                    'tanggal_donasi' => null, // Tidak ada tanggal donasi karena tidak disetujui
                    'deskripsi_request' => $request->deskripsi_request,
                    'status_request' => $request->status_request,
                    'nama_barang' => '-', // Tidak ada barang karena tidak disetujui
                    'nama_penerima' => '-', // Tidak ada penerima
                ];
            });

        // Kirimkan data ke Blade
        return view('owner.DashboardHistoryDonasi', compact('donasiApproved', 'donasiRejected', 'pegawaiLogin'));
    }

    public function updateDonasi(Request $request, $id_request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'tanggal_donasi' => 'required|date',
        ]);

        // Cari Donasi berdasarkan id_request
        $donasi = Donasi::where('id_request', $id_request)->first();

        if (!$donasi) {
            return redirect()->back()->with('error', 'Donasi tidak ditemukan.');
        }

        // Update nama penerima dan tanggal donasi
        $donasi->nama_penerima = $request->nama_penerima;
        $donasi->tanggal_donasi = $request->tanggal_donasi;
        $donasi->save();

        return redirect()->back()->with('success', 'Data Donasi berhasil diupdate.');
    }

    public function showLaporanDonasiBarang()
    {
        $pegawaiLogin = Auth::guard('pegawai')->user();

        $laporanDonasi = Donasi::with(['barang.detailTransaksiPenitipan.transaksiPenitipan.penitip', 'requestdonasi.organisasi'])->get()->map(function ($donasi) {
        $penitip = $donasi->barang?->penitip_data;

            return [
                'id_barang' => $donasi->barang->id ?? '-',
                'nama_barang' => $donasi->barang->nama_barang ?? '-',
                'id_penitip' => $penitip->id ?? '-',
                'nama_penitip' => $penitip->nama_penitip ?? '-',
                'tanggal_donasi' => $donasi->tanggal_donasi ?? '-',
                'nama_organisasi' => $donasi->requestdonasi->organisasi->nama_organisasi ?? '-',
                'nama_penerima' => $donasi->nama_penerima ?? '-',
            ];
        });

        return view('owner.DashboardLaporanDonasiBarang', compact('laporanDonasi', 'pegawaiLogin'));
    }

    public function showLaporanRequestDonasi()
    {
        $pegawaiLogin = Auth::guard('pegawai')->user();

        $requestdonasi = RequestDonasi::with('organisasi')->where('status_request', 'pending')->get();

        return view('owner.DashboardLaporanRequestDonasi', compact('requestdonasi', 'pegawaiLogin'));
    }

    public function showLaporanTransaksiPenitip()
    {
        $pegawaiLogin = Auth::guard('pegawai')->user();
        $transaksiPenitipan = Transaksipenitipan::with(['detailtransaksipenitipan.barang', 'penitip'])->get()->groupBy('id_penitip');

        return view('owner.DashboardLaporanTransaksiPenitip', compact('pegawaiLogin', 'transaksiPenitipan'));
    }
}