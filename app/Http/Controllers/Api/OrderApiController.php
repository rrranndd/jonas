<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Pelanggan;

class OrderApiController extends Controller
{
    public function index()
    {
        $orders = Order::with(['pelanggan', 'paket'])
            ->orderBy('tgl_order', 'DESC')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    public function show($kode)
    {
        $order = Order::with(['pelanggan', 'paket'])
            ->where('kode_order', $kode)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $order
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'telp_pelanggan' => 'required',
            'id_paket'       => 'required',
            'tgl_order'      => 'required|date',
            'est_selesai'    => 'required|date'
        ]);

        $pelanggan = Pelanggan::where('nama_pelanggan', $request->nama_pelanggan)->first();

        if (!$pelanggan) {
            $pelanggan = Pelanggan::create([
                'nama_pelanggan' => $request->nama_pelanggan,
                'telp_pelanggan' => $request->telp_pelanggan
            ]);
        } else {
            if ($pelanggan->telp_pelanggan !== $request->telp_pelanggan) {
                $pelanggan->update([
                    'telp_pelanggan' => $request->telp_pelanggan
                ]);
            }
        }

        $kode_order = 'JKW' . now()->format('dmy') . strtoupper(Str::random(3));
        while (Order::where('kode_order', $kode_order)->exists()) {
            $kode_order = 'JKW' . now()->format('dmy') . strtoupper(Str::random(3));
        }

        $order = Order::create([
            'kode_order'   => $kode_order,
            'tgl_order'    => $request->tgl_order,
            'est_selesai'  => $request->est_selesai,
            'status_order' => 'pending',
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'id_paket'     => $request->id_paket,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Order berhasil dibuat',
            'data'    => $order->load(['pelanggan', 'paket'])
        ], 201);
    }

    public function update(Request $request, $kode)
    {
        $order = Order::where('kode_order', $kode)->firstOrFail();

        $order->update($request->only([
            'tgl_order',
            'est_selesai',
            'status_order',
            'id_paket'
        ]));

        return response()->json([
            'status'  => 'success',
            'message' => 'Order berhasil diperbarui',
            'data'    => $order->load(['pelanggan', 'paket'])
        ]);
    }

    public function destroy($kode)
    {
        $order = Order::where('kode_order', $kode)->firstOrFail();
        $order->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Order berhasil dihapus'
        ]);
    }
}
