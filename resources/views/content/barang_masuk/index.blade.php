@extends('layout.main')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Data Barang Masuk</h3>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">Data Barang Masuk</h4>
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#addRowModal">
                            <i class="fa fa-plus"></i> Tambah Barang Masuk
                        </button>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Modal Tambah -->
                        <div class="modal fade" id="addRowModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.barang-masuk.store') }}">
                                        @csrf
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">Tambah Barang Masuk</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="barang_id" class="form-label">Nama Barang</label>
                                                <select name="barang_id" id="barang_id" class="form-control" required>
                                                    <option value="" disabled selected>--Pilih Nama Barang--</option>
                                                    @foreach($barang as $brg)
                                                        <option value="{{ $brg->id }}">{{ $brg->nama_barang }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label>Kode Transaksi (Otomatis)</label>
                                                <input type="text" class="form-control" value="Otomatis" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label for="supplier_id" class="form-label">Supplier</label>
                                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                                    <option value="" disabled selected>--Pilih Nama Supplier--</option>
                                                    @foreach($supplier as $sup)
                                                        <option value="{{ $sup->id }}">{{ $sup->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label>Tanggal Masuk</label>
                                                <input name="tanggal_masuk" type="date" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Jumlah</label>
                                                <input name="jumlah" type="number" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Harga Beli</label>
                                                <input name="harga_beli" type="text" class="form-control rupiah" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Keterangan</label>
                                                <input name="keterangan" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" class="btn btn-primary">Tambah</button>
                                            <button type="button" class="btn btn-danger"
                                                data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Supplier</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Harga Beli</th>
                                        <th>Keterangan</th>
                                        <th>User</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barangmasuk as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><span class="badge bg-warning text-dark">{{ $item->kode_transaksi }}</span></td>
                                            <td>{{ $item->barang->nama_barang }}</td>
                                            <td>{{ $item->stok_masuk }}</td>
                                            <td>{{ $item->supplier->nama }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('Y-m-d') }}</td>
                                            <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>{{ $item->user->name }}</td>
                                            <td>
                                                <!-- Edit Button -->
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $item->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.barang-masuk.destroy', $item->id) }}"
                                                    method="POST" style="display:inline;" id="delete-form-{{ $item->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete({{ $item->id }})">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST"
                                                        action="{{ route('admin.barang-masuk.update', $item->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header border-0">
                                                            <h5 class="modal-title">Edit Barang Masuk</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label>Kode Transaksi</label>
                                                                <input name="kode_transaksi" type="text" class="form-control"
                                                                    value="{{ $item->kode_transaksi }}" required readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Barang</label>
                                                                <select name="barang_id" class="form-control" required>
                                                                    @foreach($barang as $brg)
                                                                        <option value="{{ $brg->id }}" {{ $item->barang_id == $brg->id ? 'selected' : '' }}>
                                                                            {{ $brg->nama_barang }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Supplier</label>
                                                                <select name="supplier_id" class="form-control" required>
                                                                    @foreach($supplier as $sup)
                                                                        <option value="{{ $sup->id }}" {{ $item->supplier_id == $sup->id ? 'selected' : '' }}>
                                                                            {{ $sup->nama }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Tanggal Masuk</label>
                                                                <input name="tanggal_masuk" type="date" class="form-control"
                                                                    value="{{ $item->tanggal_masuk }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Jumlah</label>
                                                                <input name="jumlah" type="number" class="form-control"
                                                                    value="{{ $item->stok_masuk }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Harga Beli</label>
                                                                <input name="harga_beli" type="text" class="form-control rupiah"
                                                                    value="{{ $item->harga_beli }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Keterangan</label>
                                                                <input name="keterangan" type="text" class="form-control"
                                                                    value="{{ $item->keterangan }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $barangmasuk->links('pagination::bootstrap-5') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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