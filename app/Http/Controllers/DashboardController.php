<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Paket;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggan = Pelanggan::count();

        $totalOrder = Order::count();

        $totalPendapatan = Invoice::sum('grand_total');

        $paketTerlaris = Invoice::selectRaw('id_paket, COUNT(*) as total')
            ->groupBy('id_paket')
            ->orderBy('total', 'DESC')
            ->with('paket')
            ->take(3)
            ->get();

        return view('dashboard.index', [
            'totalPelanggan' => $totalPelanggan,
            'totalOrder'     => $totalOrder,
            'totalPendapatan'=> $totalPendapatan,
            'paketTerlaris'  => $paketTerlaris
        ]);
    }
}
