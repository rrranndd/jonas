<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $data = Paket::all();
        return view('paket.index', compact('data'));
    }

    public function store(Request $request)
    {
        Paket::create([
            'kode_paket'   => $request->kode_paket,
            'nama_paket'   => $request->nama_paket,
            'harga_paket'  => $request->harga_paket
        ]);

        return back()->with('success', 'Paket berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        Paket::where('id_paket', $id)->update([
            'kode_paket'   => $request->kode_paket,
            'nama_paket'   => $request->nama_paket,
            'harga_paket'  => $request->harga_paket
        ]);

        return back()->with('success', 'Paket berhasil diupdate');
    }

    public function destroy($id)
    {
        Paket::where('id_paket', $id)->delete();
        return back()->with('success', 'Paket berhasil dihapus');
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $data = Paket::where(function($q) use ($keyword) {
                    $q->where('nama_paket', 'like', "%$keyword%")
                    ->orWhere('kode_paket', 'like', "%$keyword%");
                })
                ->orderBy('id_paket', 'ASC')
                ->get();

        return response()->json([
            'html' => view('paket.list', compact('data'))->render()
        ]);
    }


}
