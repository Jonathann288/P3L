<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kategoribarang extends Model
{
    protected $table = 'kategoribarang';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;
    protected $fillable = ['nama_kategori', 'nama_sub_kategori'];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_kategori', 'id_kategori');
    }
}
