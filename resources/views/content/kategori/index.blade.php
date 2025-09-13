@extends('layout.main')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Data Kategori</h3>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">Data Kategori</h4>
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#addRowModal">
                            <i class="fa fa-plus"></i> Tambah Kategori
                        </button>
                    </div>

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- FORM PENCARIAN --}}
                        <form action="{{ route('admin.kategori.index') }}" method="GET" class="mb-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari kategori barang..." value="{{ request('search') }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fa fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Modal untuk Tambah KATEGORI -->
                        <div class="modal fade" id="addRowModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="formAddRow" method="POST" action="{{ route('admin.kategori.store') }}">
                                        @csrf
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">Tambah Kategori</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>kategori</label>
                                                <input name="nama_kategori" type="text" class="form-control" required>
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

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $kategori)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $kategori->nama_kategori }}</td>
                                            <td>
                                                <!-- Tombol Edit -->
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editRowModal{{ $kategori->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <!-- Modal Edit -->
                                                <div class="modal fade" id="editRowModal{{ $kategori->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST"
                                                                action="{{ route('admin.kategori.update', $kategori->id) }}">
                                                                @csrf
                                                                @method('POST')
                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title">Edit Kategori</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>Kategori</label>
                                                                        <input name="nama_kategori" type="text"
                                                                            class="form-control"
                                                                            value="{{ $kategori->nama_kategori }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-0">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Update</button>
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Tombol Hapus -->
                                                <form action="{{ route('admin.kategori.destroy', $kategori->id) }}"
                                                    method="POST" style="display:inline;" id="delete-form-{{ $kategori->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete({{ $kategori->id }})">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>

                                                <script>
                                                    function confirmDelete(kategoriId) {
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
                                                                document.getElementById('delete-form-' + kategoriId).submit();
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
@endsection