@extends('layout.main')

@section('content')
    <div class="container mt-6">
        <div class="page-inner">
            <h3 class="fw-bold mb-4">Kasir Penjualan</h3>

            <div class="row">
                <!-- Form input barang -->
                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Input Barang</h5>
                            <form action="{{ route('penjualan.addToCart') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="kode_barang" class="form-label">Kode / Nama Barang</label>
                                    <input type="text" id="kode_barang" name="kode_barang" class="form-control"
                                        placeholder="Contoh: BRG-001 atau Nama Barang" required>

                                </div>
                                <div class="mb-3">
                                    <label for="nama_barang" class="form-label">Nama Barang</label>
                                    <input type="text" id="nama_barang" name="nama_barang" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga Satuan</label>
                                    <input type="text" id="harga" name="harga" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" id="jumlah" name="jumlah" class="form-control" value="1" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Tambahkan ke Daftar</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Daftar belanja dan pembayaran -->
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Daftar Belanja</h5>

                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cart = session()->get('cart', []);
                                        $total = 0;
                                    @endphp
                                    @foreach ($cart as $index => $item)
                                        @php
                                            $subtotal = $item['harga'] * $item['jumlah'];
                                            $total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>{{ $item['nama_barang'] }}</td>
                                            <td>{{ number_format($item['harga'], 0, ',', '.') }}</td>
                                            <td>{{ $item['jumlah'] }}</td>
                                            <td>{{ number_format($subtotal, 0, ',', '.') }}</td>
                                            <td>
                                                <form action="{{ route('penjualan.remove', $index) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <strong>Total:</strong>
                                <div class="w-50">
                                    <input type="hidden" id="total_raw" value="{{ $total }}">
                                    <input type="text" value="Rp {{ number_format($total, 0, ',', '.') }}"
                                        class="form-control text-end" readonly>
                                </div>
                            </div>

                            <form action="{{ route('penjualan.simpan') }}" method="POST" id="formPembayaran">
                                @csrf

                                <div class="mb-2">
                                    <label for="bayar" class="form-label">Tunai (Bayar)</label>
                                    <input type="text" class="form-control" id="bayar" name="bayar"
                                        placeholder="Contoh: 30000" inputmode="numeric" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Kembalian:</label>
                                    <input type="text" id="kembalian" class="form-control" readonly>
                                    <p id="peringatan" class="text-danger mt-1" style="display: none;">Uang tidak cukup!</p>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fa fa-check-circle"></i> Simpan
                                    </button>

                                    <!-- Tombol Cetak Modal -->
                                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                                        data-bs-target="#modalStruk" {{ session('struk') ? '' : 'disabled' }}>
                                        <i class="fa fa-print"></i> Cetak
                                    </button>

                                    <a href="{{ route('penjualan.reset') }}" class="btn btn-secondary w-100">
                                        <i class="fa fa-refresh"></i> Reset
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Struk -->
    <div class="modal fade" id="modalStruk" tabindex="-1" aria-labelledby="modalStrukLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" id="printable-struk">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalStrukLabel">Struk Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    @if (session('struk'))
                        {!! session('struk') !!}
                    @else
                        <p>Struk belum tersedia.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button onclick="printStruk()" class="btn btn-primary w-100">Print</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function formatRupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }

        function onlyNumbers(str) {
            return str.replace(/[^\d]/g, '');
        }

        function printStruk() {
            var originalContent = document.body.innerHTML;
            var printArea = document.getElementById('printable-struk').innerHTML;
            document.body.innerHTML = printArea;
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload(); // reset tampilan setelah print
        }

        document.addEventListener('DOMContentLoaded', function () {
            const bayarInput = document.getElementById('bayar');
            const totalInput = document.getElementById('total_raw');
            const kembalianInput = document.getElementById('kembalian');
            const peringatan = document.getElementById('peringatan');

            bayarInput.addEventListener('input', function () {
                let bayarVal = onlyNumbers(bayarInput.value);
                bayarVal = bayarVal ? parseInt(bayarVal) : 0;

                bayarInput.value = bayarVal.toLocaleString('id-ID');

                const total = parseInt(totalInput.value) || 0;
                const kembalian = bayarVal - total;

                if (kembalian < 0) {
                    kembalianInput.value = formatRupiah(0);
                    peringatan.style.display = 'block';
                } else {
                    kembalianInput.value = formatRupiah(kembalian);
                    peringatan.style.display = 'none';
                }
            });
        });
        document.getElementById('kode_barang').addEventListener('input', function () {
            const keyword = this.value;

            if (keyword.length < 2) return; // minimal 2 karakter baru cari

            fetch('/penjualan/cari-barang?kode=' + encodeURIComponent(keyword))
                .then(response => response.json())
                .then(data => {
                    if (data.nama_barang && data.harga) {
                        document.getElementById('nama_barang').value = data.nama_barang;
                        document.getElementById('harga').value = data.harga;
                    } else {
                        document.getElementById('nama_barang').value = '';
                        document.getElementById('harga').value = '';
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                });
        });

    </script>
@endsection