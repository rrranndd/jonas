<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'paket';
    protected $primaryKey = 'id_paket';
    public $timestamps = false;

    protected $fillable = [
        'kode_paket',
        'nama_paket',
        'harga_paket',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'id_paket', 'id_paket');
    }
}
