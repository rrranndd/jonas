<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $invoiceHariIni = Invoice::whereDate('tgl_invoice', $today)->get();

        $totalPendapatanHariIni = $invoiceHariIni->sum('dibayar');
        $totalTransaksiHariIni = $invoiceHariIni->count();

        $order_pending = Order::where('status_order', 'pending')->count();
        $order_selesai = Order::where('status_order', 'selesai')->count();

        $grafik = Invoice::selectRaw('DATE(tgl_invoice) as tanggal, SUM(dibayar) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'ASC')
            ->where('tgl_invoice', '>=', Carbon::now()->subDays(7))
            ->get();

        return view('laporan.index', compact(
            'invoiceHariIni',
            'totalPendapatanHariIni',
            'totalTransaksiHariIni',
            'order_pending',
            'order_selesai',
            'grafik'
        ));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'dari' => 'required|date',
            'sampai' => 'required|date'
        ]);

        $data = Invoice::whereBetween('tgl_invoice', [$request->dari, $request->sampai])
            ->orderBy('tgl_invoice', 'ASC')
            ->get();

        return view('laporan.filter', compact('data'));
    }

    public function export()
    {
        $fileName = 'laporan_jonas_' . date('d-m-Y') . '.csv';

        $laporan = Invoice::with('order.pelanggan')->orderBy('tgl_invoice', 'DESC')->get();

        $header = [
            'No Invoice',
            'Pelanggan',
            'Kode Order',
            'Total',
            'Status',
            'Tanggal Invoice'
        ];

        $callback = function () use ($laporan, $header) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $header);

            foreach ($laporan as $row) {
                fputcsv($file, [
                    $row->no_invoice,
                    $row->order->pelanggan->nama_pelanggan,
                    $row->order->kode_order,
                    $row->grand_total,
                    $row->status_bayar,
                    $row->tgl_invoice
                ]);
            }

            fclose($file);
        };

        return response()->stream(
            $callback,
            200,
            [
                "Content-Type"        => "text/csv",
                "Content-Disposition" => "attachment; filename={$fileName}"
            ]
        );
    }

}
