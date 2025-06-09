<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Penitip;
use App\Models\topseller;
use Carbon\Carbon;

class TopSellerControllerAPI extends Controller
{
    public function getTopSellers()
    {
        // 1. Ambil Top Seller Bulan Berjalan (Potensial) - Logika ini tetap sama
        $currentMonthStart = Carbon::now()->startOfMonth()->toDateString();
        $currentMonthEnd = Carbon::now()->endOfMonth()->toDateString();

        $currentTopSellerData = DB::table('detailtransaksipenjualan as dts')
            ->join('transaksipenjualan as ts', 'dts.id_transaksi_penjualan', '=', 'ts.id_transaksi_penjualan')
            ->join('barang as b', 'dts.id_barang', '=', 'b.id_barang')
            ->join('detailtransaksipenitipan as dtp', 'b.id_barang', '=', 'dtp.id_barang')
            ->join('transaksipenitipan as tp', 'dtp.id_transaksi_penitipan', '=', 'tp.id_transaksi_penitipan')
            ->select('tp.id_penitip')
            ->whereBetween('ts.tanggal_transaksi', [$currentMonthStart, $currentMonthEnd])
            ->where('ts.status_pembayaran', 'Lunas')
            ->groupBy('tp.id_penitip')
            ->orderByRaw('COUNT(DISTINCT ts.id_transaksi_penjualan) DESC')
            ->first();

        $currentTopSeller = null;
        if ($currentTopSellerData) {
            $currentTopSeller = Penitip::find($currentTopSellerData->id_penitip);
        }

        // 2. [PERUBAHAN] Ambil daftar pemenang Top Seller dari Januari hingga bulan lalu
        $historicalWinners = [];
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->month - 1;

        for ($month = 1; $month <= $lastMonth; $month++) {
            $date = Carbon::createFromDate($currentYear, $month, 1);
            $startDate = $date->startOfMonth()->toDateString();
            $endDate = $date->endOfMonth()->toDateString();

            $winner = DB::table('detailtransaksipenjualan as dts')
                ->join('transaksipenjualan as ts', 'dts.id_transaksi_penjualan', '=', 'ts.id_transaksi_penjualan')
                ->join('barang as b', 'dts.id_barang', '=', 'b.id_barang')
                ->join('detailtransaksipenitipan as dtp', 'b.id_barang', '=', 'dtp.id_barang')
                ->join('transaksipenitipan as tp', 'dtp.id_transaksi_penitipan', '=', 'tp.id_transaksi_penitipan')
                ->join('penitip', 'tp.id_penitip', '=', 'penitip.id_penitip')
                ->select(
                    'penitip.id_penitip', 'penitip.nama_penitip', 'penitip.email_penitip',
                    'penitip.total_poin', 'penitip.jumlah_penjualan', 'penitip.Rating_penitip', // dan kolom lain
                    DB::raw('COUNT(DISTINCT ts.id_transaksi_penjualan) as total_transaksi'),
                    DB::raw('SUM(b.harga_barang) as total_penjualan_value')
                )
                ->whereBetween('ts.tanggal_transaksi', [$startDate, $endDate])
                ->where('ts.status_pembayaran', 'Lunas')
                ->groupBy(
                    'penitip.id_penitip', 'penitip.nama_penitip', 'penitip.email_penitip',
                    'penitip.total_poin', 'penitip.jumlah_penjualan', 'penitip.Rating_penitip'
                )
                ->orderByDesc('total_transaksi')
                ->orderByDesc('total_penjualan_value')
                ->first();

            if ($winner) {
                // Menambahkan nama bulan dan tahun ke dalam data pemenang
                $winner->month_name = $date->isoFormat('MMMM YYYY');
                $historicalWinners[] = $winner;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data top seller berhasil diambil.',
            'data' => [
                'current_month_topseller' => $currentTopSeller,
                'historical_winners' => array_reverse($historicalWinners), // Dibalik agar bulan terbaru di atas
            ]
        ]);
    }
}
