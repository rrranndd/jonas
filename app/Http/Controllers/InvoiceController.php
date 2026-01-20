<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $data = Invoice::with(['order.pelanggan','order.paket'])
            ->orderBy('tgl_invoice','DESC')
            ->get();

        return view('invoice.index', compact('data'));
    }

    public function create()
    {
        $order = Order::with(['pelanggan','paket'])
                    ->whereDoesntHave('invoice')
                    ->get();

        $no_invoice = $this->generateInvoiceNumber();

        return view('invoice.create', compact('order', 'no_invoice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_invoice'   => 'required',
            'kode_order'   => 'required',
            'metode'       => 'required',
            'bank'         => 'nullable',
            'status_bayar' => 'required',
            'dibayar'      => 'required|numeric|min:0',
            'jml_orang'    => 'required|numeric|min:0',
            'harga_orang'  => 'required|numeric|min:0',
        ]);

        $order = Order::with('paket')->where('kode_order', $request->kode_order)->firstOrFail();

        $hargaPaket = $order->paket->harga_paket;
        $tambah = $request->jml_orang * $request->harga_orang;
        $subtotal = $hargaPaket + $tambah;

        $sisa = max($subtotal - $request->dibayar, 0);

        Invoice::create([
            'no_invoice'   => $request->no_invoice,
            'kode_order'   => $request->kode_order,
            'id_paket'     => $order->id_paket,
            'jml_orang'    => $request->jml_orang,
            'harga_orang'  => $request->harga_orang,
            'subtotal'     => $subtotal,
            'grand_total'  => $subtotal,
            'dibayar'      => $request->dibayar,
            'kembalian'    => 0,
            'metode'       => $request->metode,
            'bank_tujuan'  => $request->bank,
            'status_bayar' => $request->status_bayar,
            'tgl_invoice'  => now()
        ]);

        return redirect()->route('invoice.index')
            ->with('success', 'Invoice berhasil dibuat!');
    }

    public function show($no)
    {
        $invoice = Invoice::with(['order.pelanggan','order.paket'])
                        ->where('no_invoice', $no)
                        ->firstOrFail();

        return view('invoice.show', compact('invoice'));
    }

    public function orderDetail($kode)
    {
        $order = Order::with('paket')->where('kode_order', $kode)->first();

        if (!$order) {
            return response()->json(['error' => 'Order tidak ditemukan'], 404);
        }

        return response()->json([
            'nama_paket'  => $order->paket->nama_paket,
            'harga_paket' => $order->paket->harga_paket,
            'id_paket'    => $order->id_paket
        ]);
    }

    public function lunasi(Request $request, $no)
    {
        $invoice = Invoice::where('no_invoice', $no)->firstOrFail();

        $bayar = $request->bayar;
        $invoice->dibayar += $bayar;

        if ($invoice->dibayar >= $invoice->grand_total) {
            $invoice->status_bayar = 'Lunas';

            $order = $invoice->order;
            $order->status_order = 'selesai';
            $order->save();
        }

        $invoice->save();

        return back()->with('success', 'Pembayaran telah dilunasi!');
    }

    private function generateInvoiceNumber()
    {
        $date = now()->format('dmy'); // 170126
        $random = strtoupper(Str::random(3));

        return "JKWPRBW{$date}{$random}";
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $data = Invoice::with(['order.pelanggan', 'order.paket'])
            ->where('no_invoice', 'LIKE', "%$keyword%")
            ->orWhereHas('order.pelanggan', function($q) use ($keyword) {
                $q->where('nama_pelanggan', 'LIKE', "%$keyword%");
            })
            ->orWhereHas('order', function($q) use ($keyword) {
                $q->where('kode_order', 'LIKE', "%$keyword%");
            })
            ->orderBy('tgl_invoice', 'DESC')
            ->get();

        return response()->json([
            'html' => view('invoice.list', compact('data'))->render()
        ]);
    }

}
