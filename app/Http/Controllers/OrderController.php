<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pelanggan;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\WhatsAppService;

class OrderController extends Controller
{
    public function index()
    {
        $data = Order::with(['pelanggan', 'paket'])
                    ->orderBy('kode_order', 'DESC')
                    ->get();

        $paket = Paket::orderBy('nama_paket')->get();

        return view('order.index', compact('data', 'paket'));
    }

    private function generateOrderCode()
    {
        $date = now()->format('dmy');
        $rand = strtoupper(Str::random(3));

        $kode = "JKW{$date}{$rand}";

        while (Order::where('kode_order', $kode)->exists()) {
            $rand = strtoupper(Str::random(3));
            $kode = "JKW{$date}{$rand}";
        }

        return $kode;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'telp_pelanggan' => 'required',
            'id_paket'       => 'required',
            'tgl_order'      => 'required|date',
            'est_selesai'    => 'required|date',
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

        $kode_order = $this->generateOrderCode();

        Order::create([
            'kode_order'   => $kode_order,
            'tgl_order'    => $request->tgl_order,
            'est_selesai'  => $request->est_selesai,
            'catatan'      => $request->catatan,
            'status_order' => 'pending',
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'id_paket'     => $request->id_paket,
        ]);

        $hp = preg_replace('/^0/', '62', $pelanggan->telp_pelanggan);

        WhatsAppService::send(
            $hp,
            "Pesanan Anda berhasil dibuat. Kode Order: ".$kode_order
        );

        return back()->with('success', "Order berhasil ditambahkan dengan kode: $kode_order");
    }



    public function update(Request $request, $kode)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'telp_pelanggan' => 'required',
            'id_paket'       => 'required',
            'tgl_order'      => 'required',
            'est_selesai'    => 'required',
            'status_order'   => 'required'
        ]);

        $order = Order::where('kode_order', $kode)->firstOrFail();

        $pelanggan = Pelanggan::find($order->id_pelanggan);
        $pelanggan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'telp_pelanggan' => $request->telp_pelanggan,
        ]);

        $order->update([
            'id_paket'     => $request->id_paket,
            'tgl_order'    => $request->tgl_order,
            'est_selesai'  => $request->est_selesai,
            'catatan'      => $request->catatan,
            'status_order' => $request->status_order,
        ]);

        return back()->with('success', 'Order berhasil diperbarui');
    }


    public function destroy($kode)
    {
        Order::where('kode_order', $kode)->delete();
        return back()->with('success', 'Order dihapus');
    }

    public function exportSimple()
    {
        $fileName = 'laporan_invoice.csv';

        $invoices = Invoice::with('order.pelanggan')->get();

        $header = [
            "No Invoice", 
            "Pelanggan", 
            "Kode Order", 
            "Total", 
            "Status"
        ];

        $callback = function() use ($invoices, $header) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $header);

            foreach ($invoices as $inv) {
                fputcsv($file, [
                    $inv->no_invoice,
                    $inv->order->pelanggan->nama_pelanggan,
                    $inv->order->kode_order,
                    $inv->grand_total,
                    $inv->status_bayar
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-Type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName"
        ]);
    }

}
