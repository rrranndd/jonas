@forelse ($data as $i => $p)
<tr>
    <td>{{ $i + 1 }}</td>
    <td>{{ $p->nama_pelanggan }}</td>
    <td>{{ $p->telp_pelanggan }}</td>
    <td>{{ $p->orders_count }} order</td>

    <td>
        <a href="{{ route('pelanggan.order', $p->id_pelanggan) }}"
           class="btn btn-sm btn-primary">
           Lihat Order
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center text-muted">Tidak ada data pelanggan.</td>
</tr>
@endforelse
