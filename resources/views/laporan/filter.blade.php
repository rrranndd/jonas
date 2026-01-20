@extends('layouts.master')
@section('title','Filter Laporan')

@section('content')
<div class="container-fluid">

    <h4 class="fw-bold mb-3">Hasil Filter Laporan</h4>

    <a href="{{ route('laporan.export') }}" class="btn btn-success mb-3">
        Export Excel
    </a>


    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>No Invoice</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $i => $d)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $d->no_invoice }}</td>
                <td>{{ $d->order->pelanggan->nama_pelanggan }}</td>
                <td>Rp {{ number_format($d->grand_total) }}</td>
                <td>{{ $d->tgl_invoice }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
