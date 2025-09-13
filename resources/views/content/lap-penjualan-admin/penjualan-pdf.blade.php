<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #000; }
        h2 { text-align: center; margin-bottom: 10px; }
        h4 { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        .summary-table td { border: none; }
        .text-success { color: green; }
        .header { text-align: center; margin-bottom: 10px; }
        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header">
        <strong style="font-size: 16px;">Toko Bangunan Mandala</strong><br>
        Alamat: Simpang Lambau, Jorong Rajawali<br>
        No. Telepon: 0821-xxxx-xxxx<br>
        Email: tokobangunanmandala@email.com
    </div>
<hr>
    <h4 style="text-align: center; margin-top: 20px;">Laporan Penjualan Tahun {{ $tahun }}{{ $bulan ? ' - Bulan ' . \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') : '' }}</h4>

    <table class="summary-table">
        <tr>
            <td><strong>Total Transaksi</strong></td>
            <td>{{ $totalTransaksi }}</td>
            <td><strong>Jumlah Barang Terjual</strong></td>
            <td>{{ $jumlahBarangTerjual }}</td>
        </tr>
        <tr>
            <td><strong>Total Modal</strong></td>
            <td>Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
            <td><strong>Total Penjualan</strong></td>
            <td>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Pendapatan</strong></td>
            <td colspan="3">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h4>Detail Transaksi</h4>
    <table border="1" cellspacing="0" cellpadding="6">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Faktur</th>
                <th>User</th>
                <th>Total Modal</th>
                <th>Total Penjualan</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $p)
                @php
                    $modal = $p->detailPenjualan->sum(fn($d) => $d->jumlah * $d->barang->harga_beli);
                    $penjualanHarga = $p->detailPenjualan->sum(fn($d) => $d->jumlah * $d->harga);
                    $laba = $penjualanHarga - $modal;
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('H:i') }}</td>
                    <td>{{ $p->kode_penjualan }}</td>
                    <td>{{ $p->user->name ?? '-' }}</td>
                    <td>Rp {{ number_format($modal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($penjualanHarga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($laba, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}</p>
    </div>

</body>
</html>
