@extends('layout.main')

@section('content')
<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Dashboard Admin</h3>
      </div>
    </div>

    <!-- Statistik -->
    <div class="row">
      <!-- Total Barang -->
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-primary bubble-shadow-small">
                  <i class="fas fa-boxes"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Total Barang</p>
                  <h4 class="card-title">{{ $totalBarang }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Barang Masuk -->
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-info bubble-shadow-small">
                  <i class="fas fa-dolly"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Barang Masuk</p>
                  <h4 class="card-title">{{ $totalBarangMasuk }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Supplier -->
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-success bubble-shadow-small">
                  <i class="fas fa-truck"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Supplier</p>
                  <h4 class="card-title">{{ $totalSupplier }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Penjualan -->
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                  <i class="fas fa-shopping-cart"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Penjualan</p>
                  <h4 class="card-title">{{ $totalPenjualan }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Grafik Penjualan -->
    <div class="row mt-4">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <h5>Grafik Penjualan Bulanan</h5>
          </div>
          <div class="card-body">
            <canvas id="salesChart" height="100"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Transaksi Terbaru dan Stok Kurang -->
    <div class="row mt-4">
      <!-- Transaksi Terbaru -->
      <div class="col-md-7">
        <div class="card card-round">
          <div class="card-header">
            <h5>Transaksi Terbaru</h5>
          </div>
          <div class="card-body">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Pembeli</th>
                  <th>Tanggal</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach($transaksiTerbaru as $index => $transaksi)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaksi->user->name ?? 'User' }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('Y-m-d') }}</td>
                    <td>Rp {{ number_format($transaksi->total, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Stok Kurang -->
      <div class="col-md-5">
        <div class="card card-round">
          <div class="card-header">
            <h5>Stok Barang Kurang dari 5</h5>
          </div>
          <div class="card-body">
            <ul class="list-group">
              @foreach($stokKurang as $barang)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  {{ $barang->nama_barang }}
                  <span class="badge bg-danger rounded-pill">{{ $barang->stok }} pcs</span>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Barang Terlaris -->
    <div class="row mt-4">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <h5 >Barang Terlaris (Top 5)</h5>
          </div>
          <div class="card-body">
            @if ($barangTerlaris->count() > 0)
              <ul class="list-group">
                @foreach ($barangTerlaris as $barang)
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $barang->nama_barang }}
                    <span class="badge bg-primary rounded-pill">{{ $barang->total_terjual }} terjual</span>
                  </li>
                @endforeach
              </ul>
            @else
              <p class="text-muted">Belum ada data penjualan.</p>
            @endif
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('salesChart').getContext('2d');
  const salesChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: json(range(1, 12)),
      datasets: [{
        label: 'Penjualan',
        data: json($dataGrafik),
        backgroundColor: 'rgba(255, 111, 0, 0.6)',
        borderColor: '#ff6f00',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          min: 0,
          max: 20000000,
          ticks: {
            stepSize: 5000000,
            callback: function (value) {
              return 'Rp ' + (value / 1000000).toLocaleString() + ' Juta';
            }
          }
        }
      }
    }
  });
</script>
@endsection
