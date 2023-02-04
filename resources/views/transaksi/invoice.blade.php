<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .invoice {
            width: 70mm;
        }

        table {
            width: 100%;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        hr {
            border-top: 1px solid #8c8b8b;
        }
    </style>
</head>
<body onload="javascript:window.print()">
    <div class="invoice">
        <h3 class="center">{{ $outlet->nama }}</h3>
        <p class="center">
            {{ $outlet->alamat }} <br> {{ $outlet->tlp }}
        </p>
        <hr>
        <p>
            Kode Transaksi : {{ $transaksi->kode_invoice }} <br>
            Tanggal : {{ date('d/m/Y H:i:s', strtotime($transaksi->tgl)) }} <br>
            Nama Pelanggan : {{ $member->nama }} <br>
            Kasir : {{ $user->nama }}
        </p>
        <hr>

        <table>
            @foreach ($items as $item)
                <tr>
                    <td>
                        {{ $item->qty }} {{ $item->nama_paket }}
                        x {{ number_format($item->harga,0,',','.') }} <br>
                        <small>Ket : {{ $item->keterangan }}</small>
                    </td>
                    <td class="right">
                        {{ number_format($item->harga * $item->qty,0,',','.') }}
                    </td>
                </tr>
            @endforeach
        </table>

        <hr>

        <p class="right">
            Sub Total : {{ number_format($transaksi->sub_total,0,',','.') }} <br>
            Diskon : {{ number_format($transaksi->diskon,0,',','.') }} <br>
            Biaya Tambahan : {{ number_format($transaksi->biaya_tambahan,0,',','.') }}
            <br>
            Pajak PPN(10%) : {{ number_format($transaksi->pajak,0,',','.') }} <br>
            Total : {{ number_format($transaksi->total_bayar,0,',','.') }}
            @if ($transaksi->dibayar == 'dibayar')
            <br>
                Tunai : {{ number_format($transaksi->cash,0,',','.') }} <br>
                Kembalian : {{ number_format($transaksi->kembalian,0,',','.') }} <br>
            @endif
            @if ($transaksi->dibayar == 'dibayar')
                <h3 class="center">Terima Kasih</h3>
            @endif
        </p>
    </div>
</body>
</html>
