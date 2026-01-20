@extends('layouts.master')

@section('title', 'Riwayat Order')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Riwayat Order — {{ $pelanggan->nama_pelanggan }}</h4>

        <a href="/pelanggan" class="btn btn-secondary">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Informasi Pelanggan</h5>

            <table class="table table-borderless mb-0">
                <tr>
                    <th style="width: 180px;">Nama Pelanggan</th>
                    <td>: {{ $pelanggan->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <th>No. Telepon</th>
                    <td>: {{ $pelanggan->telp_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Total Order</th>
                    <td>: <span class="fw-bold">{{ $pelanggan->orders_count }}</span> order</td>
                </tr>
            </table>

        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Daftar Order</h5>

            @if ($orders->isEmpty())
                <div class="alert alert-info">Pelanggan ini belum memiliki riwayat order.</div>
            @else

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="60">No</th>
                        <th>Kode Order</th>
                        <th>Paket</th>
                        <th>Tanggal Order</th>
                        <th>Estimasi Selesai</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $i => $o)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $o->kode_order }}</td>
                        <td>{{ $o->paket->nama_paket ?? '-' }}</td>
                        <td>{{ $o->tgl_order }}</td>
                        <td>{{ $o->est_selesai }}</td>

                        <td>
                            @if ($o->status_order == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif ($o->status_order == 'proses')
                                <span class="badge bg-primary">Proses</span>
                            @else
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @endif

        </div>
    </div>

</div>

@endsection
