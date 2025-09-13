@extends('layout.main')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Data Supplier</h3>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">Data Supplier</h4>
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#addRowModal">
                            <i class="fa fa-plus"></i> Tambah Supplier
                        </button>
                    </div>

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                                      <form action="{{ route('admin.supplier.index') }}" method="GET" class="mb-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama supplier..." value="{{ request('search') }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fa fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Modal untuk Tambah User -->
                        <div class="modal fade" id="addRowModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="formAddRow" method="POST" action="{{ route('admin.supplier.store') }}">
                                        @csrf
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">Tambah Supplier</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Nama</label>
                                                <input name="nama" type="text" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Alamat</label>
                                                <input name="alamat" type="text" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Perusahaan</label>
                                                <input name="perusahaan" type="text" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>No Telepon</label>
                                                <input name="no_telepon" type="text" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" class="btn btn-primary">Tambah</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Perusahaan</th>
                                        <th>No Telepon</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $supplier)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $supplier->nama }}</td>
                                            <td>{{ $supplier->alamat }}</td>
                                            <td>{{ $supplier->perusahaan }}</td>
                                            <td>{{ $supplier->no_telepon }}</td>
                                            <td>
                                                <!-- Tombol Edit -->
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editRowModal{{ $supplier->id }}">
                                                    <i class="fa fa-edit"></i> 
                                                </button>

                                                <!-- Modal Edit -->
                                                <div class="modal fade" id="editRowModal{{ $supplier->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST" action="{{ route('admin.supplier.update', $supplier->id) }}">
                                                                @csrf
                                                                @method('POST')
                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title">Edit Supplier</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>Nama</label>
                                                                        <input name="nama" type="text" class="form-control" value="{{ $supplier->nama }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>Alamat</label>
                                                                        <input name="alamat" type="text" class="form-control" value="{{ $supplier->alamat }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>Perusahaan</label>
                                                                        <input name="perusahaan" type="text" class="form-control" value="{{ $supplier->perusahaan }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>No Telepon</label>
                                                                        <input name="no_telepon" type="text" class="form-control" value="{{ $supplier->no_telepon }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-0">
                                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                     <!-- Tombol Hapus -->
<form action="{{ route('admin.supplier.destroy', $supplier->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $supplier->id }}">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $supplier->id }})">
        <i class="fa fa-times"></i> 
    </button>
</form>

<script>
    function confirmDelete(supplierId) {
        // SweetAlert Konfirmasi
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna menekan 'Ya, hapus', kirim form
                document.getElementById('delete-form-' + supplierId).submit();
            }
        });
    }
</script>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
    {{ $data->links('pagination::bootstrap-5') }}
</div>
                        </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
