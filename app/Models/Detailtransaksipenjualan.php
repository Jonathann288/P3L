<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detailtransaksipenjualan extends Model
{
    protected $table = 'detailtransaksipenjualan';
    protected $primaryKey = 'id_detail_transaksi_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'id_transaksi_penjualan',
        'id_barang',
        'total_harga',
        'rating_untuk_penitip'
    ];

    protected $casts = [
        'total_harga' => 'float',
        'rating_untuk_penitip' => 'integer'
    ];

    public function transaksipenjualan()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'id_transaksi_penjualan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function transaksiPenitipan()
    {
        // Asumsi: tabel ini punya 'id_transaksi_penitipan' sebagai FK ke 'transaksipenitipan'
        return $this->belongsTo(TransaksiPenitipan::class, 'id_transaksi_penitipan', 'id_transaksi_penitipan');
    }
}
