<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class diskusi extends Model
{
    protected $table = 'diskusi';
    protected $primaryKey = 'id_forum_diskusi';
    public $timestamps = false;

    protected $fillable = [
        'id_pegawai',
        'id_pembeli',
        'pesan',
        'tanggal_diskusi',
    ];

    protected $casts = [
        'tanggal_diskusi' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
}
