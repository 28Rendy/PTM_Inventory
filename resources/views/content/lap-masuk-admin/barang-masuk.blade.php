@extends('layout.main')

@section('content')
    <div class="container py-5" style="margin-top: 100px;">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h4 class="mb-4 fw-semibold text-orange">Laporan Barang Masuk</h4>

                <form action="{{ route('laporan.masuk') }}" method="GET" class="row g-3 align-items-end mb-4">
                    <div class="col-md-3">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('bulan', $bulan ?? now()->month) == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select">
                            @for($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('tahun', $tahun ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
                        <a href="{{ route('laporan.masuk.excel', request()->only(['bulan', 'tahun'])) }}"
                            class="btn btn-success me-2">Export Excel</a>
                        <a href="{{ route('laporan.masuk.pdf', request()->only(['bulan', 'tahun'])) }}"
                            class="btn btn-danger">Export PDF</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Supplier</th>
                                <th>Jumlah</th>
                                <th>Tanggal Masuk</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangMasuk as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                    <td>{{ $item->supplier->nama ?? '-' }}</td>
                                    <td>{{ $item->stok_masuk }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('Y-m-d') }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                </tr> 
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
