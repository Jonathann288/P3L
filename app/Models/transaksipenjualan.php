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
        'status_transaksi',
        'id_kurir',
        'tanggal_ambil',
        'no_nota'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'tanggal_lunas' => 'datetime',
        'tanggal_kirim' => 'datetime',
        'tanggal_ambil' => 'datetime',
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

    public function detailTransaksi()
    {
        return $this->hasMany(detailtransaksipenjualan::class, 'id_transaksi_penjualan');
    }


    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function kurir()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }


}
