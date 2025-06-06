<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class komisi extends Model
{
    protected $table = 'komisi';
    protected $primaryKey = 'id_komisi';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_transaksi_penjualan',
        'id_penitip',
        'id_pegawai',
        'komisi_penitip',
        'komisi_reusemart',
        'komisi_hunter',
    ];

    protected $casts = [
        'komisi_penitip' => 'float',
        'komisi_reusemart' => 'float',
        'komisi_hunter' => 'float',
    ];

    public function transaksipenjualan()
    {
        return $this->belongsTo(transaksipenjualan::class, 'id_transaksi_penjualan');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
    
    public function barang()
    {
        return $this->hasManyThrough(
            Barang::class,
            detailtransaksipenjualan::class,
            'id_transaksi_penjualan', // Foreign key di detailtransaksipenjualan yang mengarah ke transaksi
            'id_barang',              // Foreign key di barang
            'id_transaksi_penjualan', // Local key di komisi
            'id_barang'               // Local key di detailtransaksipenjualan
        );
    }
    
    public function hunter()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}