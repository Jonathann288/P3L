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
        'foto_barang' => 'array',
    ];

   public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip', 'id_penitip');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksiPenitipan::class, 'id_transaksi_penitipan', 'id_transaksi_penitipan');
    }

    // Accessor untuk foto_barang
    public function getFotoBarangAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    // Mutator untuk foto_barang
    public function setFotoBarangAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['foto_barang'] = json_encode($value);
        } elseif (is_string($value)) {
            $this->attributes['foto_barang'] = $value;
        } else {
            $this->attributes['foto_barang'] = json_encode([]);
        }
    }
}