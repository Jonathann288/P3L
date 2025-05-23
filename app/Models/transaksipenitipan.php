<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksipenitipan extends Model
{
    protected $table = 'transaksipenitipan';
    protected $primaryKey = 'id_transaksi_penitipan';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_pegawai',
        'id_penitip',
        'tanggal_penitipan',
        'tanggal_akhir_penitipan',
        'tanggal_batas_pengambilan',
        'tanggal_pengambilan_barang',
        'foto_barang',
    ];

    protected $casts = [
        'tanggal_penitipan' => 'datetime',
        'tanggal_akhir_penitipan' => 'datetime',
        'tanggal_batas_pengambilan' => 'datetime',
        'tanggal_pengambilan_barang' => 'datetime',
        'foto_barang' => 'json',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    // Accessor untuk foto_barang - memastikan selalu return array
    public function getFotoBarangAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }
        return is_array($value) ? $value : [];
    }

    // Mutator untuk foto_barang - memastikan disimpan sebagai JSON
    public function setFotoBarangAttribute($value)
    {
        $this->attributes['foto_barang'] = is_array($value) ? json_encode($value) : $value;
    }
}
