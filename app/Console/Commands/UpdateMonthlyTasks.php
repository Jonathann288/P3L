<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Penitip;
use App\Models\Barang;
use App\Models\topseller;
use Carbon\Carbon;

class UpdateMonthlyTasks extends Command
{
    protected $signature = 'tasks:updatemonthly';
    protected $description = 'Calculate previous months top seller and award points.';

    public function handle()
    {
        $this->info('Starting monthly tasks (Top Seller Calculation)...');

        $this->calculateTopSeller();
        // [HAPUS] Logika untuk update status barang dihapus dari sini

        $this->info('Monthly tasks completed successfully.');
        return 0;
    }

   private function calculateTopSeller()
    {
        $this->info('Calculating top seller for the previous month from completed sales...');
        $previousMonth = Carbon::now()->subMonth();
        $startDate = $previousMonth->startOfMonth()->toDateString();
        $endDate = $previousMonth->endOfMonth()->toDateString();

        $topSellerData = DB::table('detailtransaksipenjualan as dts')
            ->join('transaksipenjualan as ts', 'dts.id_transaksi_penjualan', '=', 'ts.id_transaksi_penjualan')
            ->join('barang as b', 'dts.id_barang', '=', 'b.id_barang')
            ->join('detailtransaksipenitipan as dtp', 'b.id_barang', '=', 'dtp.id_barang')
            ->join('transaksipenitipan as tp', 'dtp.id_transaksi_penitipan', '=', 'tp.id_transaksi_penitipan')
            ->select(
                'tp.id_penitip',
                DB::raw('COUNT(DISTINCT ts.id_transaksi_penjualan) as total_transaksi'),
                DB::raw('SUM(b.harga_barang) as total_penjualan_value')
            )
            ->whereBetween('ts.tanggal_transaksi', [$startDate, $endDate])

            
            ->where('ts.status_pembayaran', 'Lunas')
            
            ->groupBy('tp.id_penitip')
            ->orderByDesc('total_transaksi')
            ->orderByDesc('total_penjualan_value')
            ->first();

        if ($topSellerData) {
             $penitip = Penitip::find($topSellerData->id_penitip);
            if ($penitip) {
                
                $bonusPoin = floor($topSellerData->total_penjualan_value * 0.01);
                $penitip->total_poin += $bonusPoin;
                $penitip->badge = 'Top Seller ' . $previousMonth->isoFormat('MMMM YYYY');
                $penitip->save();

                // Simpan ke tabel topseller untuk rekam jejak
                topseller::updateOrCreate(
                    ['tanggal_mulai' => $startDate, 'tanggal_selesai' => $endDate],
                    ['id_penitip' => $penitip->id_penitip]
                );

                $this->info("Top seller found: {$penitip->nama_penitip}. Awarded {$bonusPoin} points.");
            }
        } else {
            $this->info('No completed sales recorded in the previous month.');
        }
    }
    
    // [HAPUS] Fungsi private updateAbandonedItemsStatus() dihapus dari file ini
}
