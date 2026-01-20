<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';
    protected $primaryKey = 'no_invoice';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'no_invoice',
        'kode_order',
        'id_paket',
        'jml_orang',
        'harga_orang',
        'subtotal',
        'grand_total',
        'dibayar',
        'kembalian',
        'metode',
        'bank_tujuan',
        'status_bayar',
        'tgl_invoice'
    ];

    public function order() {
        return $this->belongsTo(Order::class, 'kode_order', 'kode_order');
    }

    public function paket() {
        return $this->belongsTo(Paket::class, 'id_paket', 'id_paket');
    }
}
