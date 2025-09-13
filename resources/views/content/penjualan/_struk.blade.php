<div style="font-family: monospace; font-size: 12px; max-width: 250px; margin: auto;">
    <div style="text-align: center;">
        <h5 style="margin: 0;">TOKO MANDALA</h5>
        <small>Jl. Simpang Lambau Nagari Tigo jangko</small><br>
        <small>Telp: 0812-3456-7890</small>
    </div>
    <hr style="border: 1px dashed #000;">

    <p style="margin: 0;">Kode   : {{ $penjualan->kode_penjualan }}</p>
    <p style="margin: 0;">Tanggal: {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y H:i') }}</p>

    <hr style="border: 1px dashed #000;">
    
    @foreach ($penjualan->detailPenjualan as $item)
        <div style="margin-bottom: 5px;">
            <strong>{{ strtoupper($item->nama_barang) }}</strong><br>
            <span>{{ $item->jumlah }} x {{ number_format($item->harga) }}</span>
            <span style="float: right;">{{ number_format($item->subtotal) }}</span>
        </div>
    @endforeach

    <hr style="border: 1px dashed #000;">

    <p style="margin: 0;">
        <strong>Total</strong>
        <span style="float: right;"><strong>Rp {{ number_format($penjualan->total) }}</strong></span>
    </p>
    <p style="margin: 0;">
        Bayar
        <span style="float: right;">Rp {{ number_format($penjualan->bayar) }}</span>
    </p>
    <p style="margin: 0 0 5px 0;">
        Kembali
        <span style="float: right;">Rp {{ number_format($penjualan->kembalian) }}</span>
    </p>

    <hr style="border: 1px dashed #000;">
    
    <div style="text-align: center;">
        <p style="margin: 0;">--- TERIMA KASIH ---</p>
        <p style="margin: 0;">*** Selamat Belanja Kembali ***</p>
    </div>
</div>
