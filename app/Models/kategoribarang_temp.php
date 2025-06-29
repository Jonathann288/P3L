<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategoribarang extends Model
{
    protected $table = 'kategoribarang';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;
    protected $fillable = ['nama_kategori', 'nama_sub_kategori'];
}
