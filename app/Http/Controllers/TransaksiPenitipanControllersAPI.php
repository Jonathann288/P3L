<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailTransaksiPenitipan;
use App\Models\transaksipenitipan;
use Exception;

class TransaksiPenitipanControllersAPI extends Controller
{
    /**
     * Mengambil riwayat barang yang pernah dititipkan oleh Penitip yang sedang login.
     * Endpoint: GET /api/penitip/history
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistoryForPenitip(Request $request)
    {
        try {
            // Mengambil user penitip yang sedang login melalui guard 'sanctum'
            $penitip = Auth::user();

            if (!$penitip) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penitip tidak terautentikasi.'
                ], 401);
            }
            $transaksi = transaksipenitipan::where('id_penitip', $penitip->getKey())
                // 2. Eager load relasi bertingkat: dari transaksi -> ke detail -> ke barang.
                ->with(['detailtransaksipenitipan.barang'])
                ->orderByDesc('tanggal_penitipan') // Urutkan berdasarkan tanggal transaksi terbaru
                ->get();
            if ($transaksi->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada riwayat barang titipan.',
                    'data' => []
                ], 200);
            }

            // Ambil semua detail transaksi yang dimiliki oleh penitip tersebut
            // dan muat relasi ke barang dan transaksi penitipannya (eager loading)
            $detailTransaksi = DetailTransaksiPenitipan::whereHas('transaksipenitipan', function ($query) use ($penitip) {
                // Pastikan untuk membandingkan dengan primary key yang benar dari model Penitip
                $query->where('id_penitip', $penitip->id_penitip);
            })->with(['barang', 'transaksipenitipan'])->orderByDesc('id_detail_transaksi_penitipan')->get();

            // Jika tidak ada histori, kembalikan response sukses dengan data kosong
            if ($detailTransaksi->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada riwayat barang titipan.',
                    'data' => []
                ], 200);
            }

            // Format data agar lebih mudah dan ringan untuk digunakan di frontend
            $formattedData = $detailTransaksi->map(function ($detail) {
                // Penanganan jika relasi barang atau transaksipenitipan null
                if (!$detail->barang || !$detail->transaksipenitipan) {
                    return null;
                }

                return [
                    'id' => $detail->transaksipenitipan->getKey(), // KEY SUDAH DIUBAH
                    'harga_barang' => (float) $detail->barang->harga_barang,
                    'id_barang' => $detail->barang->id_barang,
                    'nama_barang' => $detail->barang->nama_barang,
                    'status_barang' => $detail->barang->status_barang,
                    // Ambil foto pertama dari array foto_barang jika ada
                    'foto_barang' => is_array($detail->barang->foto_barang)? array_map(function ($foto) { return asset($foto);}, $detail->barang->foto_barang)
                    : [],
                    'tanggal_penitipan' => $detail->transaksipenitipan->tanggal_penitipan->toFormattedDateString(),
                    'tanggal_akhir_penitipan' => $detail->transaksipenitipan->tanggal_akhir_penitipan->toFormattedDateString(),
                    'tanggal_batas_pengambilan' => $detail->transaksipenitipan->tanggal_batas_pengambilan ? \Carbon\Carbon::parse($detail->transaksipenitipan->tanggal_batas_pengambilan)->toDateString() : null,
                ];
            })->filter()->values(); // Hapus item null dari koleksi

            return response()->json([
                'success' => true,
                'message' => 'Data histori berhasil diambil.',
                'data' => $formattedData
            ], 200);

        } catch (Exception $e) {
            // Catat error untuk debugging di sisi server
            \Log::error('Error getHistoryForPenitip: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat mengambil riwayat.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Anda bisa menambahkan method-method lain untuk API di sini,
    // misalnya untuk perpanjang masa penitipan atau atur tanggal pengambilan versi API.
}