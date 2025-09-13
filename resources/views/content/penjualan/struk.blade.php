<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Penjualan</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
        }
        .text-center {
            text-align: center;
        }
        table {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="text-center">
        <strong>MANDALA BANGUNAN</strong><br>
        Jl. Simpang Lambau Nagari Tigo Jangko<br>
        ================================
    </div>
    <p>Tanggal: {{ $penjualan->tanggal }}</p>
    <table>
        @foreach ($penjualan->detailPenjualan as $item)
        <tr>
            <td>{{ $item->nama_barang }}</td>
            <td>x{{ $item->jumlah }}</td>
            <td style="text-align: right;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>
    <hr>
    <p>Total     : Rp {{ number_format($penjualan->total, 0, ',', '.') }}</p>
    <p>Bayar     : Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</p>
    <p>Kembalian : Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</p>
    <div class="text-center">
        ================================<br>
        TERIMA KASIH<br>
        ================================
    </div>
</body>
</html>
