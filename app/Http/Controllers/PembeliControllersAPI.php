<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailTransaksiPenitipan;

class PembeliControllersAPI extends Controller
{
    // ... (method-method lainnya)

    // METHOD getHistoryTransaksi YANG DIPERBARUI
    public function getHistoryTransaksi(Request $request)
    {

        try {
            $penitip = Auth::user();

            if (!$penitip) {
                return response()->json(['success' => false, 'message' => 'User tidak terautentikasi'], 401);
            }

            // Gunakan getKey() untuk keamanan, ini akan mengambil nilai primary key 'id_penitip'
            $penitipId = $penitip->getKey();

            $history = DetailTransaksiPenitipan::with(['barang', 'transaksipenitipan'])
                ->whereHas('transaksipenitipan', function ($query) use ($penitipId) {
                    $query->where('id_penitip', $penitipId);
                })
                ->get();

            // --- INI ADALAH BAGIAN PALING PENTING YANG DIPERBAIKI ---
            $formattedData = $history->map(function ($detail) {

                // 1. Lakukan pengecekan untuk memastikan relasi tidak null
                if (!$detail->transaksipenitipan || !$detail->barang) {
                    return null; // Abaikan data yang tidak lengkap
                }

                // 2. Ambil foto dengan lebih aman
                $foto = $detail->barang->foto_barang;
                $foto_url = null;
                if (is_array($foto) && count($foto) > 0) {
                    $foto_url = $foto[0];
                } elseif (is_string($foto)) {
                    $foto_url = $foto;
                }

                return [
                    // 3. Ambil Primary Key dengan getKey() agar lebih pasti
                    'id_transaksi_penitipan' => $detail->transaksipenitipan->getKey(),
                    'harga_barang' => (float) $detail->barang->harga_barang, // Casting ke float untuk konsistensi

                    // Data lain yang sudah ada
                    'id_barang' => $detail->barang->getKey(),
                    'nama_barang' => $detail->barang->nama_barang,
                    'status_barang' => $detail->barang->status_barang,
                    'foto_barang' => $foto_url,

                    // 4. Format tanggal dengan Carbon untuk hasil yang konsisten
                    'tanggal_penitipan' => \Carbon\Carbon::parse($detail->transaksipenitipan->tanggal_penitipan)->toDateString(), // Format: YYYY-MM-DD
                    'tanggal_akhir_penitipan' => \Carbon\Carbon::parse($detail->transaksipenitipan->tanggal_akhir_penitipan)->toDateString(),
                    'tanggal_batas_pengambilan' => $detail->transaksipenitipan->tanggal_batas_pengambilan ? \Carbon\Carbon::parse($detail->transaksipenitipan->tanggal_batas_pengambilan)->toDateString() : null,
                ];
            })->filter()->values(); // 5. Gunakan filter() untuk menghapus null & values() untuk re-index array

            return response()->json([
                'success' => true,
                'message' => 'Riwayat berhasil diambil',
                'data' => $formattedData,
            ], 200);

        } catch (\Exception $e) {
            // Tambahkan Log untuk memudahkan debugging di server
            \Log::error('Error getHistoryTransaksi: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
