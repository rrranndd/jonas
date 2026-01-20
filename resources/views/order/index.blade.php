@extends('layouts.master')

@section('title', 'Data Order')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Data Order</h4>

        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalTambahOrder">
            + Tambah Order
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode Order</th>
                        <th>Pelanggan</th>
                        <th>Paket</th>
                        <th>Tanggal Order</th>
                        <th>Estimasi Selesai</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($data as $i => $o)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $o->kode_order }}</td>
                        <td>{{ $o->pelanggan->nama_pelanggan }}</td>
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

                        <td>
                            <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $o->kode_order }}">
                                Edit
                            </button>

                            <a href="{{ route('order.delete', $o->kode_order) }}"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Hapus order ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>

<div class="modal fade" id="modalTambahOrder" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('order.store') }}" method="POST" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Tambah Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <label>Kode Order</label>
                <input type="text" name="kode_order"
                    value="JKW{{ date('dmy') }}"
                    class="form-control" readonly>

                <label class="mt-3">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan"  id="nama_pelanggan" class="form-control" required>

                <label class="mt-3">Telepon Pelanggan</label>
                <input type="text" name="telp_pelanggan" id="telp_pelanggan"  class="form-control" required>

                <label class="mt-3">Paket</label>
                <select name="id_paket" class="form-control" required>
                    <option value="">-- Pilih Paket --</option>
                    @foreach ($paket as $p)
                        <option value="{{ $p->id_paket }}">{{ $p->nama_paket }}</option>
                    @endforeach
                </select>

                <label class="mt-3">Tanggal Order</label>
                <input type="datetime-local" name="tgl_order" class="form-control" required>

                <label class="mt-3">Estimasi Selesai</label>
                <input type="datetime-local" name="est_selesai" class="form-control" required>

                <label class="mt-3">Catatan</label>
                <textarea name="catatan" class="form-control"></textarea>

            </div>

            <div class="modal-footer">
                <button class="btn btn-dark">Simpan</button>
            </div>

            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

            <script>
            $("#nama_pelanggan").on("keyup", function() {
                let nama = $(this).val();

                if (nama.length < 2) return; // minimal 2 huruf baru cari

                $.ajax({
                    url: "{{ route('pelanggan.byName') }}",
                    type: "GET",
                    data: { nama: nama },
                    success: function(res) {
                        if (res) {
                            // Pelanggan ditemukan → isi otomatis no telepon
                            $("#telp_pelanggan").val(res.telp_pelanggan);
                        } else {
                            // Tidak ditemukan → kosongkan telepon agar bisa isi manual
                            $("#telp_pelanggan").val('');
                        }
                    }
                });
            });
            </script>

        </form>
    </div>
</div>

@foreach ($data as $o)
<div class="modal fade" id="modalEdit{{ $o->kode_order }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="/order/update/{{ $o->kode_order }}" method="POST" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Edit Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <label>Kode Order</label>
                <input type="text" class="form-control" value="{{ $o->kode_order }}" disabled>

                <label class="mt-3">Pelanggan</label>
                <input type="text" name="nama_pelanggan" class="form-control"
                       value="{{ $o->pelanggan->nama_pelanggan }}" required>

                <label class="mt-3">Telepon</label>
                <input type="text" name="telp_pelanggan" class="form-control"
                       value="{{ $o->pelanggan->telp_pelanggan }}" required>

                <label class="mt-3">Paket</label>
                <select name="id_paket" class="form-control" required>
                    <option value="">-- Pilih Paket --</option>
                    @foreach ($paket as $p)
                        <option value="{{ $p->id_paket }}"
                            {{ $o->id_paket == $p->id_paket ? 'selected' : '' }}>
                            {{ $p->nama_paket }}
                        </option>
                    @endforeach
                </select>

                <label class="mt-3">Tanggal Order</label>
                <input type="datetime-local" name="tgl_order" class="form-control"
                       value="{{ \Carbon\Carbon::parse($o->tgl_order)->format('Y-m-d\TH:i') }}" required>

                <label class="mt-3">Estimasi Selesai</label>
                <input type="datetime-local" name="est_selesai" class="form-control"
                       value="{{ \Carbon\Carbon::parse($o->est_selesai)->format('Y-m-d\TH:i') }}" required>

                <label class="mt-3">Catatan</label>
                <textarea name="catatan" class="form-control">{{ $o->catatan }}</textarea>

                <label class="mt-3">Status</label>
                <select name="status_order" class="form-control" required>
                    <option value="pending" {{ $o->status_order=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="proses" {{ $o->status_order=='proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ $o->status_order=='selesai' ? 'selected' : '' }}>Selesai</option>
                </select>

            </div>

            <div class="modal-footer">
                <button class="btn btn-dark">Simpan</button>
            </div>

        </form>
    </div>
</div>
@endforeach
@endsection
