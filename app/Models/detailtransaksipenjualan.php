<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detailtransaksipenjualan extends Model
{
    protected $table = 'detailtransaksipenjualan';
    protected $primaryKey = 'id_detail_transaksi_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'id_transaksi_penjualan',
        'id_barang',
        'total_harga',
    ];

    protected $casts = [
        'total_harga' => 'float',
    ];

    public function transaksipenjualan()
    {
        return $this->belongsTo(transaksipenjualan::class, 'id_transaksi_penjualan');
    }

    public function barang()
    {
        return $this->belongsTo(barang::class, 'id_barang');
    }
}
