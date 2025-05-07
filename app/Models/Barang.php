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
        'harga_barang' => 'float',
        'rating_barang' => 'float',
        'berat_barang' => 'float',
        'garansi_barang' => 'datetime',
    ];

    public function kategoribarang()
    {
        return $this->belongsTo(kategoribarang::class, 'id_kategori');
    }

    public function penitip()
    {
        return $this->belongsTo(penitip::class, 'id'); // Asumsi relasi ke tabel penitip
    }
}