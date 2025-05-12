<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class topseller extends Model
{
    protected $table = 'topseller';
    protected $primaryKey = 'id_penitip';
    public $timestamps = false;

    protected $fillable = [
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
}
