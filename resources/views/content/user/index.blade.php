@extends('layout.main')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Data User</h3>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">Data User</h4>
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#addRowModal">
                            <i class="fa fa-plus"></i> Tambah User
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
                        <div class="modal fade" id="addRowModal" tabindex="-1" aria-labelledby="addRowModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addRowModalLabel">Tambah User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <form id="formAddRow" action="{{ route('admin.user.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="input-name" class="form-label">Nama</label>
                                                <input id="input-name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="input-email" class="form-label">Email</label>
                                                <input id="input-email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="input-role" class="form-label">Role</label>
                                                <select id="input-role" name="role" class="form-select @error('role') is-invalid @enderror">
                                                    <option value="">-- Pilih Role --</option>
                                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                                </select>
                                                @error('role')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="input-password" class="form-label">Password</label>
                                                <input id="input-password" name="password" type="password" class="form-control @error('password') is-invalid @enderror">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Tambah</button>
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
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>
                                                <!-- Tombol Edit -->
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editRowModal{{ $user->id }}">
                                                    <i class="fa fa-edit"></i> 
                                                </button>

                                                <!-- Modal Edit -->
                                                <div class="modal fade" id="editRowModal{{ $user->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST" action="{{ route('admin.user.update', $user->id) }}">
                                                                @csrf
                                                                @method('POST')
                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title">Edit User</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>Nama</label>
                                                                        <input name="name" type="text" class="form-control" value="{{ $user->name }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Email</label>
                                                                        <input name="email" type="email" class="form-control" value="{{ $user->email }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Role</label>
                                                                        <select name="role" class="form-control" required>
                                                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                                            <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Password (kosongkan jika tidak ingin mengubah)</label>
                                                                        <input name="password" type="password" class="form-control">
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
                                                <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $user->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }})">
                                                        <i class="fa fa-times"></i> 
                                                    </button>
                                                </form>

                                                <script>
                                                    function confirmDelete(userId) {
                                                        Swal.fire({
                                                            title: 'Apakah Anda yakin?',
                                                            text: "Data ini akan dihapus secara permanen!",
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonText: 'Ya, hapus!',
                                                            cancelButtonText: 'Batal',
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                document.getElementById('delete-form-' + userId).submit();
                                                            }
                                                        });
                                                    }
                                                </script>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Modal Error dan Reset --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Buka modal jika ada error validasi
            @if ($errors->any())
                var myModal = new bootstrap.Modal(document.getElementById('addRowModal'));
                myModal.show();
            @endif

            // Reset form saat modal ditutup
            const addModal = document.getElementById('addRowModal');
            addModal.addEventListener('hidden.bs.modal', function () {
                document.getElementById('formAddRow').reset();

                // Reset semua field secara manual juga
                document.getElementById('input-name').value = '';
                document.getElementById('input-email').value = '';
                document.getElementById('input-role').value = '';
                document.getElementById('input-password').value = '';

                // Hapus error class
                document.querySelectorAll('#formAddRow .is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });
            });
        });
    </script>
@endsection
