<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDonasi extends Model
{
    protected $table = 'requestdonasi';
    protected $primaryKey = 'id_request';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_organisasi',
        'deskripsi_request',
        'tanggal_request',
        'status_request',
    ];

    protected $casts = [
        'tanggal_request' => 'datetime',
    ];

    // Relasi ke tabel organisasi
    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class, 'id_organisasi');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
