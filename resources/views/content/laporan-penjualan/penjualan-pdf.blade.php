<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Tahun {{ $tahun }} {{ $bulan ? '- Bulan ' . date('F', mktime(0, 0, 0, $bulan, 1)) : '' }}</h2>

    <table>
        <thead>
            <tr>
                <th>Total Transaksi</th>
                <th>Jumlah Barang Terjual</th>
                <th>Total Modal</th>
                <th>Total Penjualan</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $totalTransaksi }}</td>
                <td>{{ $jumlahBarangTerjual }}</td>
                <td>Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
