<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamat';
    protected $primaryKey = 'id_alamat';
    public $timestamps = false;

    protected $fillable = [
        'id_pembeli',
        'id',
        'nama_jalan',
        'kode_pos',
        'kecamatan',
        'kelurahan',
        'status_default',
        'kabupaten',
        'deskripsi_alamat',
    ];

    // Relasi ke tabel Pembeli (jika ada)
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
}