<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class claimmerchandise extends Model
{
    protected $table = 'claimmerchandise';
    protected $primaryKey = 'id_transaksi_claim_merchandise';
    public $timestamps = false;

    protected $fillable = [
        'id_pembeli',
        'id_merchandise',
        'id',
        'tanggal_claim',
        'tanggal_pengambilan',
    ];

    protected $casts = [
        'tanggal_claim' => 'datetime',
        'tanggal_pengambilan' => 'datetime',
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }

    public function merchandise()
    {
        return $this->belongsTo(merchandise::class, 'id_merchandise');
    }
}
