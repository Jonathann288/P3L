<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'id_kategori',
        'nama_barang',
        'foto_barang',
        'harga_barang',
        'deskripsi_barang',
        'masa_penitipan',
        'status_barang',
        'rating_barang',
        'berat_barang',
        'garansi_barang',
    ];

    protected $casts = [
        'harga_barang' => 'float',
        'rating_barang' => 'float',
        'berat_barang' => 'float',
        'garansi_barang' => 'datetime',
        'foto_barang' => 'array'
    ];

    public function kategoribarang()
    {
        return $this->belongsTo(kategoribarang::class, 'id_kategori');
    }

        public function kategori()
    {
        return $this->belongsTo(kategoribarang::class, 'id_kategori');
    }

        public function diskusi()
    {
        return $this->hasMany(Diskusi::class, 'id_barang', 'id_barang');
    }

    public function detailTransaksiPenitipan()
    {
        return $this->hasOne(DetailTransaksiPenitipan::class, 'id_barang');
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

        public function pegawai()
    {
        return $this->hasManyThrough(
            Pegawai::class,
            DetailTransaksiPenitipan::class,
            'id_barang', 
            'id_pegawai', 
            'id_barang', 
            'id_transaksi_penitipan' 
        )->join('transaksipenitipan', 'detail_transaksi_penitipan.id_transaksi_penitipan', '=', 'transaksipenitipan.id_transaksi_penitipan');
    }

    // Alternatif: Relasi untuk mendapatkan transaksi penitipan
    public function transaksiPenitipan()
    {
        return $this->hasManyThrough(
            TransaksiPenitipan::class,
            DetailTransaksiPenitipan::class,
            'id_barang', 
            'id_transaksi_penitipan', 
            'id_barang',
            'id_transaksi_penitipan' 
        );
    }

    // ... (properti dan method lain) ...

    public function getPenitipDataAttribute()
    {
        // Muat relasi bertingkat: Barang -> DetailTransaksiPenitipan -> TransaksiPenitipan -> Penitip
        $this->loadMissing('detailTransaksiPenitipan.transaksiPenitipan.penitip');

        if ($this->detailTransaksiPenitipan &&
            $this->detailTransaksiPenitipan->transaksiPenitipan &&
            $this->detailTransaksiPenitipan->transaksiPenitipan->penitip) {
            
            // dd($this->detailTransaksiPenitipan->transaksiPenitipan->penitip->toArray()); // UNCOMMENT UNTUK DEBUG DI SINI

            return $this->detailTransaksiPenitipan->transaksiPenitipan->penitip;
        }
        return null;
    }
}