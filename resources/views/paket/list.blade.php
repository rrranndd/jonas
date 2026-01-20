@forelse ($data as $i => $p)
<tr>
    <td class="text-center">{{ $i + 1 }}</td>
    <td>{{ $p->kode_paket }}</td>
    <td>{{ $p->nama_paket }}</td>
    <td class="text-end">Rp {{ number_format($p->harga_paket, 0, ',', '.') }}</td>

    <td class="text-center">
        <button class="btn btn-sm btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#modalEdit{{ $p->id_paket }}">
            Edit
        </button>

        <a href="/paket/delete/{{ $p->id_paket }}"
           class="btn btn-sm btn-danger"
           onclick="return confirm('Yakin ingin menghapus paket ini?')">
            Hapus
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center text-muted py-3">
        Tidak ada paket ditemukan.
    </td>
</tr>
@endforelse
