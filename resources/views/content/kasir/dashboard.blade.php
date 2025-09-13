@extends('layout.main')
@section('content')

<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Dashboard Kasir</h3>
      </div>
    </div>

    <div class="row">
      <!-- Total Transaksi Hari Ini -->
      <div class="col-md-6">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-info bubble-shadow-small">
                  <i class="fas fa-receipt"></i>
                </div>
              </div>
              <div class="col col-stats ms-3">
                <div class="numbers">
                  <p class="card-category">Transaksi Hari Ini</p>
                  <h4 class="card-title">{{ $jumlahTransaksi }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Penjualan Hari Ini -->
      <div class="col-md-6">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-success bubble-shadow-small">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>
              <div class="col col-stats ms-3">
                <div class="numbers">
                  <p class="card-category">Total Penjualan Hari Ini</p>
                  <h4 class="card-title">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Riwayat Transaksi -->
    <div class="row mt-4">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <h5>Transaksi Terbaru</h5>
          </div>
          <div class="card-body">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode</th>
                  <th>Tanggal</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($transaksiTerbaru as $index => $trx)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $trx->kode_penjualan }}</td>
                  <td>{{ $trx->tanggal }}</td>
                  <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center">Belum ada transaksi</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection
