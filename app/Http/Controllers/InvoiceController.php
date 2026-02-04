<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\WhatsAppService;

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
            'status_bayar' => 'required|in:Lunas,Belum Lunas',
            'dibayar'      => 'required|numeric|min:0',
            'jml_orang'    => 'required|numeric|min:0',
            'harga_orang'  => 'required|numeric|min:0',
        ]);

        // Ambil order + relasi
        $order = Order::with(['paket','pelanggan'])
            ->where('kode_order', $request->kode_order)
            ->firstOrFail();

        // Hitung total
        $hargaPaket = $order->paket->harga_paket;
        $tambahan   = $request->jml_orang * $request->harga_orang;
        $total      = $hargaPaket + $tambahan;

        // Tentukan status bayar SECARA LOGIS (jangan percaya input user)
        $statusBayar = $request->dibayar >= $total
            ? 'Lunas'
            : 'Belum Lunas';

        // Simpan invoice
        $invoice = Invoice::create([
            'no_invoice'   => $request->no_invoice,
            'kode_order'   => $order->kode_order,
            'id_paket'     => $order->id_paket,
            'jml_orang'    => $request->jml_orang,
            'harga_orang'  => $request->harga_orang,
            'subtotal'     => $total,
            'grand_total'  => $total,
            'dibayar'      => $request->dibayar,
            'kembalian'    => max(0, $request->dibayar - $total),
            'metode'       => $request->metode,
            'bank_tujuan'  => $request->bank,
            'status_bayar' => $statusBayar,
            'tgl_invoice'  => now()
        ]);

        // 🔥 INI KUNCI UTAMA (YANG SEBELUMNYA HILANG)
        if ($statusBayar === 'Lunas') {
            $order->status_order = 'selesai';
            $order->save();
        }

        // Kirim WA jika ada nomor
        if ($order->pelanggan && $order->pelanggan->telp_pelanggan) {

            $hp = preg_replace('/^0/', '62', $order->pelanggan->telp_pelanggan);

            $pesan = "*Invoice Dibuat*\n\n".
                    "No Invoice: {$invoice->no_invoice}\n".
                    "Kode Order: {$order->kode_order}\n".
                    "Paket: {$order->paket->nama_paket}\n".
                    "Total: Rp ".number_format($invoice->grand_total,0,',','.')."\n".
                    "Dibayar: Rp ".number_format($invoice->dibayar,0,',','.')."\n".
                    "Status: {$invoice->status_bayar}";

            WhatsAppService::send($hp, $pesan);
        }

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
        $invoice = Invoice::with('order.pelanggan')
                        ->where('no_invoice', $no)
                        ->firstOrFail();

        $bayar = $request->bayar;
        $invoice->dibayar += $bayar;

        if ($invoice->dibayar >= $invoice->grand_total) {
            $invoice->status_bayar = 'Lunas';

            $order = $invoice->order;
            $order->status_order = 'selesai';
            $order->save();
        }

        $invoice->save();

        if ($invoice->order && $invoice->order->pelanggan) {

            $hp = preg_replace('/^0/', '62', $invoice->order->pelanggan->telp_pelanggan);

            $pesan = "*Pembayaran Lunas*\n\n".
                     "No Invoice: {$invoice->no_invoice}\n".
                     "Total: Rp ".number_format($invoice->grand_total,0,',','.')."\n".
                     "Status: LUNAS\n".
                     "Terima kasih 🙏";

            WhatsAppService::send($hp, $pesan);
        }

        return back()->with('success', 'Pembayaran telah dilunasi!');
    }

    private function generateInvoiceNumber()
    {
        $date = now()->format('dmy');
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
