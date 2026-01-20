@extends('layouts.master')

@section('title', 'Data Paket')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Data Paket</h4>

        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Paket
        </button>
    </div>

    <div class="mb-3" style="max-width: 350px;">
        <input type="text" id="searchPaket" class="form-control"
               placeholder="Cari paket... (nama, kode)">
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="text-center" width="60">No</th>
                        <th width="160">Kode Paket</th>
                        <th>Nama Paket</th>
                        <th class="text-end" width="160">Harga Paket</th>
                        <th class="text-center" width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody id="paketTable">
                    @include('paket.list')
                </tbody>
            </table>

        </div>
    </div>

</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="/paket/store" method="POST" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <label class="fw-semibold">Kode Paket</label>
                <input type="text" name="kode_paket" class="form-control mb-3" required>

                <label class="fw-semibold">Nama Paket</label>
                <input type="text" name="nama_paket" class="form-control mb-3" required>

                <label class="fw-semibold">Harga Paket</label>
                <input type="number" name="harga_paket" class="form-control" required>

            </div>

            <div class="modal-footer">
                <button class="btn btn-dark">Simpan</button>
            </div>
        </form>
    </div>
</div>

@foreach ($data as $p)
<div class="modal fade" id="modalEdit{{ $p->id_paket }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="/paket/update/{{ $p->id_paket }}" method="POST" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <label class="fw-semibold">Kode Paket</label>
                <input type="text" name="kode_paket" class="form-control mb-3"
                       value="{{ $p->kode_paket }}" required>

                <label class="fw-semibold">Nama Paket</label>
                <input type="text" name="nama_paket" class="form-control mb-3"
                       value="{{ $p->nama_paket }}" required>

                <label class="fw-semibold">Harga Paket</label>
                <input type="number" name="harga_paket" class="form-control"
                       value="{{ $p->harga_paket }}" required>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-dark">Simpan</button>
            </div>

        </form>
    </div>
</div>
@endforeach

@endsection


@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$("#searchPaket").on("keyup", function() {
    let keyword = $(this).val();

    $.ajax({
        url: "{{ route('paket.search') }}",
        type: "GET",
        data: { keyword: keyword },
        success: function(res) {
            $("#paketTable").html(res.html);
        }
    });
});
</script>
@endsection
