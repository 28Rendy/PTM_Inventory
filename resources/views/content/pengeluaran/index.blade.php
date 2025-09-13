@extends('layout.main')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Data Pengeluaran</h3>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">Data Pengeluaran</h4>
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#addRowModal">
                            <i class="fa fa-plus"></i> Tambah Pengeluaran
                        </button>
                    </div>

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Modal untuk Tambah User -->
                        <div class="modal fade" id="addRowModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="formAddRow" method="POST" action="{{ route('admin.pengeluaran.store') }}">
                                        @csrf
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">Tambah Pengeluaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Deskripsi</label>
                                                <input name="deskripsi" type="text" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Nominal</label>
                                                <input name="nominal" type="number" class="form-control" required>
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
                                        <th>Deskripsi</th>
                                        <th>Nominal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $pengeluaran)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pengeluaran->deskripsi }}</td>
                                            <td>{{ $pengeluaran->nominal }}</td>
                                            <td>
                                                <!-- Tombol Edit -->
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editRowModal{{ $pengeluaran->id }}">
                                                    <i class="fa fa-edit"></i> 
                                                </button>

                                                <!-- Modal Edit -->
                                                <div class="modal fade" id="editRowModal{{ $pengeluaran->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST" action="{{ route('admin.pengeluaran.update', $pengeluaran->id) }}">
                                                                @csrf
                                                                @method('POST')
                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title">Edit Supplier</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>Deskripsi</label>
                                                                        <input name="deskripsi" type="text" class="form-control" value="{{ $pengeluaran->deskripsi }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>Nominal</label>
                                                                        <input name="nominal" type="number" class="form-control" value="{{ $pengeluaran->nominal }}" required>
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
<form action="{{ route('admin.pengeluaran.destroy', $pengeluaran->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $pengeluaran->id }}">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $pengeluaran->id }})">
        <i class="fa fa-times"></i> 
    </button>
</form>

<script>
    function confirmDelete(pengeluaranId) {
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
                document.getElementById('delete-form-' + pengeluaranId).submit();
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
