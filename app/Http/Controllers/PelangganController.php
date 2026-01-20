<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $data = Pelanggan::withCount('orders')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('nama_pelanggan', 'like', "%$keyword%")
                      ->orWhere('telp_pelanggan', 'like', "%$keyword%");
                });
            })
            ->orderBy('nama_pelanggan')
            ->get();

        return view('pelanggan.index', compact('data', 'keyword'));
    }

    public function store(Request $request)
    {
        Pelanggan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'telp_pelanggan' => $request->telp_pelanggan
        ]);

        return back()->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        Pelanggan::where('id_pelanggan', $id)->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'telp_pelanggan' => $request->telp_pelanggan
        ]);

        return back()->with('success', 'Pelanggan berhasil diupdate');
    }

    public function destroy($id)
    {
        Pelanggan::where('id_pelanggan', $id)->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus');
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $data = Pelanggan::withCount('orders')
            ->where(function($q) use ($keyword) {
                $q->where('nama_pelanggan', 'like', "%$keyword%")
                  ->orWhere('telp_pelanggan', 'like', "%$keyword%");
            })
            ->orderBy('nama_pelanggan')
            ->get();

        return response()->json([
            'html' => view('pelanggan.list', compact('data'))->render()
        ]);
    }

    public function riwayat($id)
    {
        $pelanggan = Pelanggan::withCount('orders')->findOrFail($id);

        $orders = \App\Models\Order::with('paket')
                    ->where('id_pelanggan', $id)
                    ->orderBy('tgl_order', 'DESC')
                    ->get();

        return view('pelanggan.riwayat', compact('pelanggan', 'orders'));
    }

    public function getByName(Request $request)
    {
        $nama = $request->nama;

        $pelanggan = Pelanggan::where('nama_pelanggan', $nama)->first();

        return response()->json($pelanggan);
    }

}
