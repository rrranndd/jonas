<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'kode_order';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kode_order',
        'tgl_order',
        'est_selesai',
        'catatan',
        'status_order',
        'id_pelanggan',
        'id_paket'  
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket', 'id_paket');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'kode_order', 'kode_order');
    }
}
