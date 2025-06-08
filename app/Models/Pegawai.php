<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Pegawai extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    public $timestamps = false;
    protected $fillable = [
        'id','nama_pegawai', 'tanggal_lahir_pegawai',
        'nomor_telepon_pegawai', 'email_pegawai', 'password_pegawai', 'id_jabatan',
    ];

    protected $hidden = ['password_pegawai'];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    public function getAuthPassword()
    {
        return $this->password_pegawai;
    }

    public function getTable()
    {
        return 'pegawai'; // Secara eksplisit mengembalikan nama tabel
    }
    
    public function komisi()
    {
        return $this->hasMany(Komisi::class, 'id_pegawai','id_pegawai');
    }

}
