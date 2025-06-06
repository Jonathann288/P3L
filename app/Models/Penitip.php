<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB; 
use App\Models\DetailTransaksiPenjualan;
use App\Models\TransaksiPenitipan;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class Penitip extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'foto_ktp'
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

     public function transaksiPenitipan()
    {
        // Asumsi: tabel transaksipenitipan memiliki kolom 'id_penitip' sebagai FK
        return $this->hasMany(TransaksiPenitipan::class, 'id_penitip', 'id_penitip');
    }

    public function updateAverageRating()
    {
        $barangIds = collect();

        // Muat relasi bertingkat: Penitip -> TransaksiPenitipan -> DetailTransaksiPenitipan
        // Pastikan model TransaksiPenitipan memiliki relasi detailTransaksiPenitipan()
        // dan model DetailTransaksiPenitipan memiliki atribut id_barang
        $this->loadMissing('transaksiPenitipan.detailTransaksiPenitipan');

        foreach ($this->transaksiPenitipan as $transPenitipan) {
            if ($transPenitipan->detailTransaksiPenitipan) { // Pastikan relasi detailTransaksiPenitipan ada dan tidak null
                foreach ($transPenitipan->detailTransaksiPenitipan as $detailPenitipan) {
                    if ($detailPenitipan->id_barang) {
                        $barangIds->push($detailPenitipan->id_barang);
                    }
                }
            }
        }
        
        $uniqueBarangIds = $barangIds->unique()->values();

        if ($uniqueBarangIds->isEmpty()) {
            $this->rating_penitip = 0;
        } else {
            $averageRating = DetailTransaksiPenjualan::whereIn('id_barang', $uniqueBarangIds)
                ->whereNotNull('rating_untuk_penitip')
                ->avg('rating_untuk_penitip');
            $this->rating_penitip = round($averageRating ?? 0, 2);
        }

        try {
            $this->save();
        } catch (\Exception $e) {
            Log::error("Gagal menyimpan rata-rata rating untuk penitip_id " . $this->id_penitip . ": " . $e->getMessage());
        }
    }
}
