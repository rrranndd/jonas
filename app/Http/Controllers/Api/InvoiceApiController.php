<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Invoice;
use App\Models\Order;

class InvoiceApiController extends Controller
{
    public function index()
    {
        $invoice = Invoice::with(['order.pelanggan','order.paket'])
            ->orderBy('tgl_invoice','DESC')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $invoice
        ]);
    }

    public function show($no)
    {
        $invoice = Invoice::with(['order.pelanggan','order.paket'])
            ->where('no_invoice', $no)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $invoice
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_order' => 'required',
            'metode'     => 'required',
            'dibayar'    => 'required|numeric|min:0'
        ]);

        $order = Order::with('paket')
            ->where('kode_order', $request->kode_order)
            ->firstOrFail();

        $no_invoice = 'INV' . now()->format('dmy') . strtoupper(Str::random(4));

        $invoice = Invoice::create([
            'no_invoice'   => $no_invoice,
            'kode_order'   => $order->kode_order,
            'id_paket'     => $order->id_paket,
            'subtotal'     => $order->paket->harga_paket,
            'grand_total'  => $order->paket->harga_paket,
            'dibayar'      => $request->dibayar,
            'kembalian'    => 0,
            'metode'       => $request->metode,
            'status_bayar' => 'Belum Lunas',
            'tgl_invoice'  => now()
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Invoice berhasil dibuat',
            'data'    => $invoice->load(['order.pelanggan','order.paket'])
        ], 201);
    }

    public function updateStatus(Request $request, $no)
    {
        $invoice = Invoice::where('no_invoice', $no)->firstOrFail();

        $invoice->update([
            'status_bayar' => $request->status_bayar
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Status invoice diperbarui',
            'data'    => $invoice
        ]);
    }
}
