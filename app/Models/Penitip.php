<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Penitip extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'penitip';
    protected $primaryKey = 'id_penitip';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'nama_penitip',
        'nomor_ktp',
        'email_penitip',
        'tanggal_lahir',
        'password_penitip',
        'nomor_telepon_penitip',
        'saldo_penitip',
        'total_poin',
        'badge',
        'jumlah_penjualan',
        'foto_profil',
        'rating_penitip',
    ];

    protected $hidden = [
        'password_penitip',  
        'remember_token',  
    ];

    public function getEmailForPasswordReset()
    {
        return $this->email_penitip;
    }

    public function getAuthPassword()
    {
        return $this->password_penitip;
    }
}
