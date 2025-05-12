<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detailtransaksipenitipan extends Model
{
    protected $table = 'detailtransaksipenitipan';
    protected $primaryKey = 'id_detail_transaksi_penitipan';
    public $timestamps = false;

    protected $fillable = [
        'id_transaksi_penitipan',
        'id_barang',
    ];

    public function transaksipenitipan()
    {
        return $this->belongsTo(transaksipenitipan::class, 'id_transaksi_penitipan');
    }

    public function barang()
    {
        return $this->belongsTo(barang::class, 'id_barang');
    }
}
