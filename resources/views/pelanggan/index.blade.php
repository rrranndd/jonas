@extends('layouts.master')
@section('title', 'Data Pelanggan')
@section('content')

<div class="container-fluid">

    <h4 class="fw-bold mb-4">Data Pelanggan</h4>

    <div class="mb-3" style="max-width: 350px;">
        <input type="text" id="search" class="form-control" placeholder="Cari nama atau nomor telepon...">
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Pelanggan</th>
                        <th>No. Telepon</th>
                        <th>Total Order</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody id="pelangganTable">
                    @include('pelanggan.list')
                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$("#search").on("keyup", function() {
    let keyword = $(this).val();

    $.ajax({
        url: "{{ route('pelanggan.search') }}",
        type: "GET",
        data: { keyword: keyword },
        success: function(res) {
            $("#pelangganTable").html(res.html);
        }
    });
});
</script>
@endsection
