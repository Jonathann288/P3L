<?php
// COPY SEMUA
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksipenitipan extends Model
{
    protected $table = 'transaksipenitipan';
    protected $primaryKey = 'id_transaksi_penitipan';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_pegawai',
        'id_hunter',
        'id_penitip',
        'tanggal_penitipan',
        'tanggal_akhir_penitipan',
        'tanggal_batas_pengambilan',
        'tanggal_pengambilan_barang',
    ];

    protected $casts = [
        'tanggal_penitipan' => 'datetime',
        'tanggal_akhir_penitipan' => 'datetime',
        'tanggal_batas_pengambilan' => 'datetime',
        'tanggal_pengambilan_barang' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function detailtransaksipenitipan()
    {
        return $this->hasMany(DetailTransaksiPenitipan::class, 'id_transaksi_penitipan');
    }
    public function detailTransaksiPnt()
    {
        return $this->hasMany(DetailTransaksiPenitipan::class, 'id_transaksi_penitipan');
    }

    public function hunter()
    {
        return $this->belongsTo(Pegawai::class, 'id_hunter');
    }
}