<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\Auth;

class PembeliControllersAPI extends Controller
{
    // ... (method-method lainnya)

    // METHOD getHistoryTransaksi YANG DIPERBARUI
    public function getHistoryTransaksi(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        $pembeli = Auth::user();

        $query = TransaksiPenjualan::where('id_pembeli', $pembeli->id_pembeli)
                    ->with(['detailTransaksi.barang'])
                    ->orderBy('tanggal_transaksi', 'desc');

        // LOGIKA FILTER BARU
        // Hanya terapkan filter jika kedua tanggal ada
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Menggunakan closure untuk mengelompokkan kondisi tanggal
            $query->where(function ($q) use ($startDate, $endDate) {
                // Kondisi 1: Jika tanggal_transaksi berada dalam rentang
                $q->whereBetween('tanggal_transaksi', [$startDate, $endDate])
                  // ATAU Kondisi 2: Jika tanggal_kirim berada dalam rentang
                  ->orWhereBetween('tanggal_kirim', [$startDate, $endDate]);
            });
        }

        $transaksi = $query->get();

        if ($transaksi->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada riwayat transaksi ditemukan.',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Riwayat transaksi berhasil diambil.',
            'data' => $transaksi,
        ], 200);
    }
}
