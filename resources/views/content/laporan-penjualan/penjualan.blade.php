@extends('layout.main')

@section('content')
    <div class="container-fluid" style="padding: 2rem 2rem 2rem 10px; margin-top: 80px;">
        <h4 class="mb-4 fw-semibold text-orange">Laporan Penjualan</h4>

        <form method="GET" class="row g-3 align-items-end mb-4">
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
                        <option value="{{ $y }}" {{ request('tahun', $tahun ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="bg-white shadow rounded-4 p-3 text-center">
                    <h6>Total Penjualan</h6>
                    <p class="fs-4 fw-bold">{{ $totalTransaksi }}</p>
                    <p class="text-muted small">Jumlah transaksi penjualan</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white shadow rounded-4 p-3 text-center">
                    <h6>Total Item Terjual</h6>
                    <p class="fs-4 fw-bold">{{ $jumlahBarangTerjual }}</p>
                    <p class="text-muted small">Jumlah semua barang terjual</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white shadow rounded-4 p-3 text-center">
                    <h6>Total Modal</h6>
                    <p class="fs-4 fw-bold">Rp {{ number_format($totalModal, 0, ',', '.') }}</p>
                    <p class="text-muted small">Total modal barang</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white shadow rounded-4 p-3 text-center">
                    <h6>Total Pendapatan</h6>
                    <p class="fs-4 fw-bold text-success">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
                    <p class="text-mudes small">Total keuntungan bersih</p>
                </div>
            </div>
        </div>

        <div class="table-responsive mb-3">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Faktur</th>
                        <th>User</th>
                        <th>Total Modal</th>
                        <th>Total Harga</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $modalAkhir = 0;
                        $penjualanAkhir = 0;
                        $keuntunganAkhir = 0;
                    @endphp
                    @foreach ($penjualan as $p)
                        @php
                            $modal = $p->detailPenjualan->sum(fn($d) => $d->jumlah * $d->barang->harga_beli);
                            $penjualanHarga = $p->detailPenjualan->sum(fn($d) => $d->jumlah * $d->harga);
                            $laba = $penjualanHarga - $modal;
                            $modalAkhir += $modal;
                            $penjualanAkhir += $penjualanHarga;
                            $keuntunganAkhir += $laba;
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->created_at)->format('H:i:s') }}</td>
                            <td>{{ $p->kode_penjualan }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td>Rp {{ number_format($modal, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($penjualanHarga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($laba, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <div class="bg-white shadow-sm rounded-4 p-3">
                <div class="d-flex justify-content-between mb-2">
                    <strong>Total Modal:</strong>
                    <span>Rp {{ number_format($modalAkhir, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <strong>Total Penjualan:</strong>
                    <span>Rp {{ number_format($penjualanAkhir, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <strong>Total Pendapatan:</strong>
                    <span class="text-success fw-bold">Rp {{ number_format($keuntunganAkhir, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>
@endsection