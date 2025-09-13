@extends('layout.main')

@section('content')
    <div class="container py-5" style="margin-top: 100px;">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h4 class="mb-4 fw-semibold text-orange">Laporan Stok Barang</h4>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <a href="{{ route('laporan.stok.excel') }}" class="btn btn-success me-2">Export Excel</a>
                        <a href="{{ route('laporan.stok.pdf') }}" class="btn btn-danger">Export PDF</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barang as $b)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $b->kode_barang }}</td>
                                    <td>{{ $b->nama_barang }}</td>
                                    <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                                    <td>{{ $b->stok }}</td>
                                    <td>{{ $b->satuan }}</td>
                                    <td>Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
