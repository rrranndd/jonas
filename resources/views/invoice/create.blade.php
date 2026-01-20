@extends('layouts.master')
@section('title', 'Buat Invoice')
@section('content')

<div class="container-fluid">

    <h4 class="fw-bold mb-4">Buat Invoice</h4>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('invoice.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>No Invoice</label>
                        <input type="text" name="no_invoice" value="{{ $no_invoice }}" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Metode Pembayaran</label>
                        <select name="metode" id="metode" class="form-control" required>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3" id="bankArea" style="display:none;">
                        <label>Pilih Bank</label>
                        <select name="bank" class="form-control">
                            <option value="">- Pilih Bank -</option>
                            <option value="BCA">BCA</option>
                            <option value="BRI">BRI</option>
                            <option value="Mandiri">Mandiri</option>
                            <option value="BNI">BNI</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Pilih Order</label>
                        <select name="kode_order" id="kode_order" class="form-control" required>
                            <option value="">-- Pilih Order --</option>
                            @foreach ($order as $o)
                                <option value="{{ $o->kode_order }}">
                                    {{ $o->kode_order }} — {{ $o->pelanggan->nama_pelanggan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nama Paket</label>
                        <input type="text" id="nama_paket" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Harga Paket</label>
                        <input type="text" id="harga_paket_display" class="form-control" readonly>
                        <input type="hidden" id="harga_paket" name="harga_paket">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jumlah Orang Tambahan</label>
                        <input type="number" id="jml_orang" name="jml_orang" value="0" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Harga Per Orang</label>
                        <input type="number" id="harga_orang" name="harga_orang" value="0" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jumlah Dibayar</label>
                        <input type="number" name="dibayar" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Status Pembayaran</label>
                        <select name="status_bayar" class="form-control">
                            <option value="DP">DP</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>

                    <input type="hidden" id="subtotal" name="subtotal">

                </div>

                <button class="btn btn-dark w-100 mt-3">Simpan Invoice</button>

            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $("#metode").on("change", function () {
        $("#bankArea").toggle($(this).val() === "transfer");
    });

    $("#kode_order").change(function () {
        let kode = $(this).val();
        if (!kode) return;

        fetch(`/invoice/order-detail/${kode}`)
            .then(res => res.json())
            .then(data => {
                $("#nama_paket").val(data.nama_paket);
                $("#harga_paket").val(data.harga_paket);
                $("#harga_paket_display").val(data.harga_paket.toLocaleString());

                hitungSubtotal();
            });
    });

    $("#jml_orang, #harga_orang").on("keyup change", hitungSubtotal);

    function hitungSubtotal() {
        let hargaPaket = parseInt($("#harga_paket").val()) || 0;
        let jml = parseInt($("#jml_orang").val()) || 0;
        let hargaOrg = parseInt($("#harga_orang").val()) || 0;

        let total = hargaPaket + (jml * hargaOrg);
        $("#subtotal").val(total);
    }
</script>
@endsection
