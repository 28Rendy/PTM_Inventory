<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 0;
            font-size: 12px;
        }
        hr {
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            font-size: 11px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Toko Bangunan Mandala</h2>
        <p>Alamat: Simpang Lambau, Jorong Rajawali</p>
        <p>No. Telepon: 0812-xxxx-xxxx</p>
        <p>Email: tokobangunanmandala@example.com</p>
    </div>

    <hr>

    <h4 style="text-align: center; margin-top: 20px;">Laporan Stok Barang</h4>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Kode</th>
                <th style="width: 20%;">Nama Barang</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 8%;">Stok</th>
                <th style="width: 10%;">Satuan</th>
                <th style="width: 15%;">Harga Beli</th>
                <th style="width: 15%;">Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $index => $b)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $b->kode_barang }}</td>
                    <td>{{ $b->nama_barang }}</td>
                    <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $b->stok }}</td>
                    <td>{{ $b->satuan }}</td>
                    <td>Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
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
