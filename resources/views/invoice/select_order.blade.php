@extends('layouts.master')
@section('title','Buat Invoice')

@section('content')

<div class="container-fluid">

    <h4 class="fw-bold mb-4">📌 Pilih Order Untuk Dibuatkan Invoice</h4>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Kode Order</th>
                <th>Pelanggan</th>
                <th>Paket</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($orders as $o)
            <tr>
                <td>{{ $o->kode_order }}</td>
                <td>{{ $o->pelanggan->nama_pelanggan }}</td>
                <td>{{ $o->paket->nama_paket }}</td>
                <td>
                    <a href="{{ route('invoice.fromOrder', $o->kode_order) }}" class="btn btn-success">
                        Buat Invoice
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection
