<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Masuk</title>
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
        <p>No. Telepon: 0821-xxxx-xxxx</p>
        <p>Email: tokobangunanmandala@email.com</p>
    </div>

    <hr>

    <h4 style="text-align: center; margin-top: 20px;">Laporan Barang Masuk</h4>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Kode Barang</th>
                <th style="width: 25%;">Nama Barang</th>
                <th style="width: 20%;">Supplier</th>
                <th style="width: 10%;">Jumlah</th>
                <th style="width: 15%;">Tanggal Masuk</th>
                <th style="width: 15%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangMasuk as $item)
                <tr>
                    <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $item->supplier->nama ?? '-' }}</td>
                    <td>{{ $item->stok_masuk }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

   <div class="footer">
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Pimpinan' }}</p>
    </div>
</body>
</html>
