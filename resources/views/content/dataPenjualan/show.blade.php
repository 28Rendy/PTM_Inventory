@extends('layout.main')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Detail Penjualan</h3>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5>Informasi Penjualan</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Bayar</th>
                            <td>Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Kembalian</th>
                            <td>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td>{{ $penjualan->user->name ?? '-' }}</td>
                        </tr>
                    </table>

                    <hr>

                    <h5>Barang Terjual</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detail as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                       <td>
                                        <form id="delete-form-{{ $penjualan->id }}" action="{{ route('Datapenjualan.destroy', $penjualan->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $penjualan->id }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total</th>
                                    <th>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Tombol Kembali di bagian bawah --}}
                    <div class="mt-4 text-start">
                        <a href="{{ route('Datapenjualan.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection