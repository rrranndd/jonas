@extends('layouts.master')
@section('title','Laporan')

@section('content')

<div class="container-fluid">

    <h4 class="fw-bold mb-3">Laporan Sistem</h4>

    <div class="row mb-3">

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Pendapatan Hari Ini</h5>
                    <h3>Rp {{ number_format($totalPendapatanHariIni) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Jumlah Transaksi Hari Ini</h5>
                    <h3>{{ $totalTransaksiHariIni }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Order Pending</h5>
                    <h3>{{ $order_pending }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">🔎 Filter Laporan</h5>

            <form action="{{ route('laporan.filter') }}" method="GET" class="row">
                <div class="col-md-4">
                    <label>Dari</label>
                    <input type="date" name="dari" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Sampai</label>
                    <input type="date" name="sampai" class="form-control" required>
                </div>

                <div class="col-md-4 mt-4">
                    <button class="btn btn-dark w-100">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">Grafik Pendapatan 7 Hari Terakhir</h5>

            <canvas id="grafikPendapatan"></canvas>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let ctx = document.getElementById('grafikPendapatan');

    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($grafik->pluck('tanggal')),
            datasets: [{
                label: 'Pendapatan',
                data: @json($grafik->pluck('total')),
                borderWidth: 3
            }]
        }
    });
</script>
@endsection
