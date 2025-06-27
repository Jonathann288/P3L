<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class merchandise extends Model
{
    protected $table = 'merchandise';
    protected $primaryKey = 'id_merchandise';
    public $timestamps = false;

    protected $fillable = [
        'id_pegawai',
        'id',
        'nama_merchandise',
        'harga_merchandise',
        'stok_merchandise',
        'foto_merchandise',
    ];

    protected $casts = [
        'harga_merchandise' => 'integer',
        'stok_merchandise' => 'integer',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
