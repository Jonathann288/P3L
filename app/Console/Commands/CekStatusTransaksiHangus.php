<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\transaksipenjualan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CekStatusTransaksiHangus extends Command
{
    protected $signature = 'transaksi:cek-hangus';
    protected $description = 'Cek transaksi yang belum diambil dalam 2 hari dan ubah statusnya menjadi Hangus';

    public function handle()
    {
        $this->info('Mulai cek transaksi hangus...');

        $transaksiList = transaksipenjualan::with('detailTransaksi.barang')
            ->where('status_transaksi', '!=', 'Hangus')
            ->whereNotNull('tanggal_ambil')
            ->get();

        $count = 0;

        foreach ($transaksiList as $transaksi) {
            $tanggalAmbil = Carbon::parse($transaksi->tanggal_ambil);
            $sekarang = Carbon::now();
            $selisihHari = $sekarang->diffInDays($tanggalAmbil);

            if ($selisihHari > 2) {
                DB::transaction(function () use ($transaksi) {
                    $transaksi->status_transaksi = 'Hangus';
                    $transaksi->save();

                    foreach ($transaksi->detailTransaksi as $detail) {
                        if ($detail->barang) {
                            $detail->barang->status_barang = 'di donasikan';
                            $detail->barang->save();
                        }
                    }
                });

                $count++;
                $this->info("Transaksi ID {$transaksi->id_transaksi_penjualan} diubah menjadi Hangus.");
            }
        }

        $this->info("Selesai, total $count transaksi diupdate.");
    }
}
