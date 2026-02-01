<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;

class PelangganApiController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::orderBy('nama_pelanggan')->get();

        return response()->json([
            'status' => 'success',
            'data' => $pelanggan
        ]);
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with(['orders.paket'])
            ->where('id_pelanggan', $id)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $pelanggan
        ]);
    }
}
