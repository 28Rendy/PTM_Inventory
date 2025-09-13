<div id="printable-struk">
    <h4>Struk Penjualan</h4>
    <p>Kode: {{ $penjualan->kode_penjualan ?? '-' }}</p>
    <p>Tanggal: {{ $penjualan->tanggal }}</p>

    <table border="1" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan->detailPenjualan as $detail)
                <tr>
                    <td>{{ $detail->nama_barang }}</td>
                    <td>Rp {{ number_format($detail->harga) }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>Rp {{ number_format($detail->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Total: <strong>Rp {{ number_format($penjualan->total) }}</strong></p>
    <p>Bayar: Rp {{ number_format($penjualan->bayar) }}</p>
    <p>Kembalian: Rp {{ number_format($penjualan->kembalian) }}</p>
</div>

<!-- Tombol Print -->
<div class="mt-3">
    <button onclick="printStruk()" class="btn btn-primary">Print</button>
</div>
