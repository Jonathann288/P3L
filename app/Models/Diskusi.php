<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diskusi extends Model
{
    protected $table = 'diskusi';
    protected $primaryKey = 'id_forum_diskusi';
    public $timestamps = false;

    protected $fillable = [
        'id_pegawai',
        'id_pembeli',
        'pesan',
        'tanggal_diskusi',
        'id_barang',
        'parent_id'
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

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function replies()
    {
        return $this->hasMany(Diskusi::class, 'parent_id');
    }
}