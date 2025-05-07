<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organisasi extends Authenticatable
{
    use HasFactory;
    protected $table = 'organisasi';

    protected $primaryKey = 'id_organisasi';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'nama_organisasi',
        'alamat_organisasi',
        'nomor_telepon',
        'email_organisasi',
        'password_organisasi',
    ];

    protected $hidden = [
        'password_organisasi',
        'token',
    ];
    
}

