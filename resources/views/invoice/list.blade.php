@foreach ($data as $i => $d)
<tr>
    <td>{{ $i + 1 }}</td>
    <td>{{ $d->no_invoice }}</td>
    <td>{{ $d->order->pelanggan->nama_pelanggan }}</td>
    <td>{{ $d->order->kode_order }}</td>
    <td>Rp {{ number_format($d->grand_total) }}</td>

    <td>
        @if($d->status_bayar === 'Lunas')
            <span class="badge bg-success">Lunas</span>
        @else
            <span class="badge bg-warning text-dark">DP</span>
        @endif
    </td>

    <td>
        <a href="{{ route('invoice.show', $d->no_invoice) }}"
           class="btn btn-sm btn-dark">
           Lihat
        </a>

        @if($d->status_bayar != 'Lunas')
            <button class="btn btn-sm btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#modalLunasi{{ $d->no_invoice }}">
                Lunasi
            </button>
        @endif
    </td>
</tr>

<div class="modal fade" id="modalLunasi{{ $d->no_invoice }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Pelunasan Invoice {{ $d->no_invoice }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            @php
                $sisa = $d->grand_total - $d->dibayar;
            @endphp

            <form action="{{ route('invoice.lunasi', $d->no_invoice) }}" method="POST">
                @csrf

                <div class="modal-body">

                    <p><b>Total:</b> Rp {{ number_format($d->grand_total) }}</p>
                    <p><b>Sudah Dibayar:</b> Rp {{ number_format($d->dibayar) }}</p>
                    <p><b>Sisa Pembayaran:</b> Rp {{ number_format($sisa) }}</p>

                    <label class="mb-1">Jumlah yang dibayar sekarang:</label>
                    <input type="number"
                        name="bayar"
                        class="form-control"
                        min="1"
                        max="{{ $sisa }}"
                        value="{{ $sisa }}"  {{-- otomatis isi sisa --}}
                        required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">
                        Simpan Pelunasan
                    </button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


@endforeach
