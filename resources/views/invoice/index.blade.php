@extends('layouts.master')
@section('title', 'Data Invoice')

@section('content')

<div class="container-fluid">

    <h4 class="fw-bold mb-4">
        Data Invoice
        <a href="{{ route('invoice.create') }}" class="btn btn-dark float-end">
            + Tambah Invoice
        </a>
    </h4>

    <div class="mb-3" style="max-width:300px;">
        <input type="text" id="searchInvoice" class="form-control"
            placeholder="Cari invoice...">
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="50">No</th>
                        <th>No Invoice</th>
                        <th>Pelanggan</th>
                        <th>Kode Order</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody id="invoiceTable">
                    @include('invoice.list')
                </tbody>

            </table>

        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    $("#searchInvoice").on("keyup", function () {
        let keyword = $(this).val();

        $.ajax({
            url: "{{ route('invoice.search') }}",
            type: "GET",
            data: { keyword: keyword },
            success: function (res) {
                $("#invoiceTable").html(res.html);
            }
        });
    });
</script>
@endsection

