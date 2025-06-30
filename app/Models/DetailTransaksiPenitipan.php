<?php
// COPY SEMUA
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksiPenitipan extends Model
{
    protected $table = 'detailtransaksipenitipan';
    protected $primaryKey = 'id_detail_transaksi_penitipan';
    public $timestamps = false;

    protected $fillable = [
        'id_transaksi_penitipan',
        'id_barang',
        'status_perpanjangan',

    ];

    public function transaksipenitipan()
    {
        return $this->belongsTo(Transaksipenitipan::class, 'id_transaksi_penitipan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function transaksipenjualan()
    {
        return $this->hasOneThrough(
            Transaksipenjualan::class,
            DetailTransaksiPenjualan::class,
            'id_barang',
            'id_transaksi_penjualan',
            'id_barang',
            'id_transaksi_penjualan'
        );
    }
}