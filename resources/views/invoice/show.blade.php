<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style>
        body {
            font-family: "Courier New", monospace;
            font-size: 12px;
            width: 280px;
            margin: 0 auto;
            padding: 5px;
        }

        .bold { font-weight: bold; }
        .center { text-align: center; }
        .right { text-align: right; }

        .line {
            border-top: 1px solid #000;
            margin: 6px 0;
        }

        table { width: 100%; }
        td { padding: 1px 0; }

        @media print {
            button { display: none !important; }
            body { margin: 0; }
        }

    </style>
</head>

<body>

    <div class="bold">Jonas Banda</div>
    <div>Tel : 0811-2005-8456</div>
    <div>Tanggal : {{ now()->format('d/m/Y H:i') }}</div>

    <div class="line"></div>

    <div class="center bold">
        BUKTI PEMBAYARAN ORDER <br>
        <small>Harap dibawa saat pengambilan order</small>
    </div>

    <div class="line"></div>

    <table>
        <tr><td>No Invoice</td><td>: {{ $invoice->no_invoice }}</td></tr>
        <tr><td>Nama</td><td>: {{ $invoice->order->pelanggan->nama_pelanggan }}</td></tr>
        <tr><td>Code Order</td><td>: {{ $invoice->order->kode_order }}</td></tr>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td>Est Tgl Selesai :</td>
            <td class="right">{{ date('d/m/Y H:i', strtotime($invoice->order->est_selesai)) }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td>{{ $invoice->order->paket->nama_paket }}</td>
            <td class="center">1</td>
            <td class="right">{{ number_format($invoice->order->paket->harga_paket) }}</td>
        </tr>

        @if($invoice->jml_orang > 0)
        <tr>
            <td>Penambahan Orang</td>
            <td class="center">{{ $invoice->jml_orang }}</td>
            <td class="right">{{ number_format($invoice->jml_orang * $invoice->harga_orang) }}</td>
        </tr>
        @endif
    </table>
    <table>
        <tr>
            <td class="bold">SUBTOTAL</td>
            <td class="right bold">{{ number_format($invoice->subtotal) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        <tr><td class="bold">Metode Pembayaran</td><td></td></tr>
        <tr>
            <td>
                @if($invoice->metode == 'cash')
                    Cash
                @else
                    Transfer {{ $invoice->bank_tujuan }}
                @endif
            </td>
            <td class="right">{{ number_format($invoice->dibayar) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td class="bold">GRAND TOTAL</td>
            <td class="right bold">{{ number_format($invoice->grand_total) }}</td>
        </tr>
        <tr>
            <td class="bold">TOTAL PEMBAYARAN</td>
            <td class="right bold">{{ number_format($invoice->dibayar) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center" style="font-size:11px;">
        Terima kasih telah bertransaksi di Jonas Photo. <br>
        Dengan melakukan transaksi ini, konsumen menyetujui <br>
        syarat dan ketentuan yang berlaku.
    </div>

    <div style="margin-bottom:10px;">
        <button onclick="window.print()" class="btn btn-dark w-100">
            Print Invoice
        </button>
    </div>

</body>
</html>
