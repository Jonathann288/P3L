<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

/**
 * Controller untuk menangani logika terkait pembeli via API.
 * Nama kelas diubah menjadi PembeliApiController untuk mengikuti konvensi Laravel.
 */class PembeliControllersAPI extends Controller
{
    /**
     * Mengambil riwayat transaksi untuk pembeli yang sedang terautentikasi.
     * Mendukung filter berdasarkan rentang tanggal.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistoryTransaksi(Request $request)
    {
        // Langkah 1: Validasi input dari request.
        // Ini memastikan bahwa 'start_date' dan 'end_date' adalah format tanggal yang valid
        // dan 'end_date' tidak mendahului 'start_date'.
        $validatedData = $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        // Langkah 2: Dapatkan informasi pembeli yang sedang login.
        $pembeli = Auth::user();

        // Jika tidak ada user yang login, kembalikan response error.
        if (!$pembeli) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Silakan login terlebih dahulu.',
            ], 401);
        }

        // Langkah 3: Bangun query dasar untuk mengambil transaksi.
        $query = TransaksiPenjualan::query()
            // Filter transaksi hanya untuk pembeli yang sedang login.
            ->where('id_pembeli', $pembeli->id_pembeli)
            // Eager load relasi untuk menghindari N+1 problem.
            // Ini akan mengambil detail transaksi beserta data barang terkait dalam satu query efisien.
            ->with(['detailTransaksi.barang'])
            // Urutkan hasil berdasarkan tanggal transaksi terbaru.
            ->orderBy('tanggal_transaksi', 'desc');

        // Langkah 4: Terapkan filter tanggal secara kondisional.
        // Menggunakan metode `when` membuat kode lebih bersih.
        // Blok kode di dalam `when` hanya akan dieksekusi jika kondisi pertamanya true.
        $query->when($request->filled(['start_date', 'end_date']), function (Builder $q) use ($validatedData) {
            $startDate = $validatedData['start_date'];
            $endDate = $validatedData['end_date'];

            // Kelompokkan kondisi OR menggunakan closure di dalam `where`.
            // Ini penting agar logika OR tidak mengganggu klausa `where('id_pembeli', ...)` di atas.
            // Query akan mencari transaksi di mana 'tanggal_transaksi' ATAU 'tanggal_kirim'
            // berada dalam rentang tanggal yang diberikan.
            $q->where(function (Builder $subQuery) use ($startDate, $endDate) {
                $subQuery->whereBetween('tanggal_transaksi', [$startDate, $endDate])
                         ->orWhereBetween('tanggal_kirim', [$startDate, $endDate]);
            });
        });

        // Langkah 5: Eksekusi query dan dapatkan hasilnya.
        $transaksi = $query->get();

        // Langkah 6: Kembalikan response dalam format JSON.
        // Memberikan pesan yang jelas jika tidak ada data yang ditemukan.
        if ($transaksi->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada riwayat transaksi yang ditemukan.',
                'data' => [],
            ], 200);
        }

        // Jika data ditemukan, kembalikan data tersebut.
        return response()->json([
            'success' => true,
            'message' => 'Riwayat transaksi berhasil diambil.',
            'data' => $transaksi,
        ], 200);
    }
}
