<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paket;

class PaketApiController extends Controller
{
    public function index()
    {
        $paket = Paket::orderBy('nama_paket')->get();

        return response()->json([
            'status' => 'success',
            'data' => $paket
        ]);
    }

    public function show($id)
    {
        $paket = Paket::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $paket
        ]);
    }

    public function store(Request $request)
    {
        $paket = Paket::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $paket
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $paket = Paket::findOrFail($id);
        $paket->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $paket
        ]);
    }

    public function destroy($id)
    {
        Paket::destroy($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Paket dihapus'
        ]);
    }

}
