<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';
    protected $fillable = [
        'pembeli_id',
        'id_barang',
        'nama_pembeli',
        'nama_barang',
        'harga_barang',
        'jumlah'
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }


}

