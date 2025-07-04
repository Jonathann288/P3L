<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    protected $table = 'donasi';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'id_barang',
        'id_request',
        'nama_penerima',
        'tanggal_donasi',
    ];

    protected $casts = [
        'tanggal_donasi' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function requestdonasi()
    {
        return $this->belongsTo(RequestDonasi::class, 'id_request');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

}
