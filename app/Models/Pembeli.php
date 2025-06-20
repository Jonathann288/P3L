<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;


class Pembeli extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    protected $table = 'pembeli';
    protected $primaryKey = 'id_pembeli';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'nama_pembeli',
        'tanggal_lahir',
        'email_pembeli',
        'password_pembeli',
        'nomor_telepon_pembeli',
        'total_poin',
        'foto_pembeli'
    ];

    protected $hidden = [
        'password_pembeli',
        'remember_token',
    ];

    // Untuk auth menggunakan kolom email_pembeli
    public function getEmailForPasswordReset()
    {
        return $this->email_pembeli;
    }

    // Untuk auth menggunakan kolom password_pembeli
    public function getAuthPassword()
    {
        return $this->password_pembeli;
    }

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'id_pembeli'); // sesuaikan foreign key
    }
    public function transaksiPenjualan()
    {
        return $this->hasMany(transaksipenjualan::class, 'pembeli_id', 'pembeli_id');
    }

    
}