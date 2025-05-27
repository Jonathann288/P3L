<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $timestamps = false;

    protected $fillable = [
        'id', 
        'id_kategori',
        'nama_barang',
        'foto_barang',
        'harga_barang',
        'deskripsi_barang',
        'masa_penitipan',
        'status_barang',
        'rating_barang',
        'berat_barang',
        'garansi_barang',
    ];

    protected $casts = [
        'harga_barang' => 'decimal:2',
        'rating_barang' => 'decimal:2',
        'berat_barang' => 'decimal:2',
        'garansi_barang' => 'datetime',
    ];

    public function kategoribarang()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori', 'id_kategori');
    }

    public function detailTransaksiPenitipan()
    {
        return $this->hasMany(DetailTransaksiPenitipan::class, 'id_barang', 'id_barang');
    }


    public function pegawai()
    {
        return $this->hasManyThrough(
            Pegawai::class,
            DetailTransaksiPenitipan::class,
            'id_barang', 
            'id_pegawai', 
            'id_barang', 
            'id_transaksi_penitipan' 
        )->join('transaksipenitipan', 'detail_transaksi_penitipan.id_transaksi_penitipan', '=', 'transaksipenitipan.id_transaksi_penitipan');
    }

    // Alternatif: Relasi untuk mendapatkan transaksi penitipan
    public function transaksiPenitipan()
    {
        return $this->hasManyThrough(
            TransaksiPenitipan::class,
            DetailTransaksiPenitipan::class,
            'id_barang', 
            'id_transaksi_penitipan', 
            'id_barang',
            'id_transaksi_penitipan' 
        );
    }
}