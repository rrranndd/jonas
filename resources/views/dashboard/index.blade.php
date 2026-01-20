@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Pelanggan</h6>
                    <h2 class="fw-bold">{{ $totalPelanggan }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Order</h6>
                    <h2 class="fw-bold">{{ $totalOrder }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Total Pendapatan</h6>
                    <h2 class="fw-bold">Rp {{ number_format($totalPendapatan) }}</h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">

            <h5 class="fw-bold mb-3">
                <i class="bi bi-box-seam"></i> Paket Paling Banyak Dipesan
            </h5>

            @forelse ($paketTerlaris as $p)
                <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded paket-item">
                    <div class="d-flex align-items-center">
                        <div class="icon-box me-3">
                            <i class="bi bi-box-fill"></i>
                        </div>
                        <span class="fw-semibold">{{ $p->paket->nama_paket }}</span>
                    </div>

                    <span class="badge bg-primary rounded-pill" style="font-size: 14px;">
                        {{ $p->total }} pesanan
                    </span>
                </div>
            @empty
                <p class="text-muted">Belum ada data pemesanan paket.</p>
            @endforelse

        </div>
    </div>

</div>

@endsection
