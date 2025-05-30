<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksipenjualan extends Model
{
    protected $table = 'transaksipenjualan';
    protected $primaryKey = 'id_transaksi_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_pembeli',
        'id_pegawai',
        'tanggal_transaksi',
        'metode_pengantaran',
        'tanggal_lunas',
        'bukti_pembayaran',
        'status_pembayaran',
        'poin',
        'tanggal_kirim',
        'ongkir',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'tanggal_lunas' => 'datetime',
        'tanggal_kirim' => 'datetime',
        'poin' => 'integer',
        'ongkir' => 'float',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function detailTransaksiPenjualan()
    {
        return $this->hasMany(DetailTransaksiPenjualan::class, 'id_transaksi_penjualan');
    }

}
