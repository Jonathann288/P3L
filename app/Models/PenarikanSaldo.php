<?php



// app/Models/PenarikanSaldo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenarikanSaldo extends Model
{
    use HasFactory;

    protected $table = 'penarikan_saldo';
    protected $primaryKey = 'id_penarikan';

    protected $fillable = [
        'id_penitip',
        'nominal_penarikan',
        'biaya_penarikan',
    ];


    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip', 'id');
    }
}