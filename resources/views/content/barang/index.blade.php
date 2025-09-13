@extends('layout.main')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Data Barang</h3>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">Data Barang</h4>
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#addRowModal">
                            <i class="fa fa-plus"></i> Tambah Barang
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
                        <form action="{{ route('admin.barang.index') }}" method="GET" class="mb-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama atau kode barang..." value="{{ request('search') }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fa fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Modal Tambah Barang -->
                        <div class="modal fade" id="addRowModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.barang.store') }}">
                                        @csrf
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">Tambah Barang</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Nama Barang</label>
                                                <input name="nama_barang" type="text" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Kode Barang (Otomatis)</label>
                                                <input type="text" class="form-control" value="Otomatis" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label>Kategori</label>
                                                <select name="kategori_id" class="form-control" required>
                                                    <option value="">-- Pilih Kategori --</option>
                                                    @foreach($kategori as $kat)
                                                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="satuan" class="form-label">Satuan</label>
                                                <select name="satuan" class="form-control" required>
                                                    <option value="">-- Pilih Satuan --</option>
                                                    <option value="pcs">Pcs</option>
                                                    <option value="lembar">Lembar</option>
                                                    <option value="batang">Batang</option>
                                                    <option value="sak">Sak</option>
                                                    <option value="kg">Kilogram (Kg)</option>
                                                    <option value="m">Meter (m)</option>
                                                    <option value="m2">Meter Persegi (m²)</option>
                                                    <option value="liter">Liter (L)</option>
                                                    <option value="box">Box</option>
                                                    <option value="pickup">Pickup</option>
                                                </select>
                                            </div>
                                           <div class="mb-3">
                                                <label>Harga Beli</label>
                                                <input name="harga_beli" type="text" class="form-control rupiah" value="{{ old('harga_beli') }}" required>
                                            </div>
                                           <div class="mb-3">
                                            <label>Harga Jual</label>
                                            <input name="harga_jual" type="text" class="form-control rupiah" value="{{ old('harga_jual') }}">
                                            @error('harga_jual')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>             
                                            <div class="mb-3">
                                                <label>Stok</label>
                                                <input name="stok" type="number" class="form-control" required>
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

                        <!-- Tabel Barang -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Kode Barang</th>
                                        <th>Kategori</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Stok</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barang as $item)
                                        <tr>
                                            <td>{{ $loop->iteration + ($barang->currentPage() - 1) * $barang->perPage() }}</td>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td><span class="badge bg-warning text-dark">{{ $item->kode_barang }}</span></td>
                                            <td>{{ $item->kategori->nama_kategori }}</td>
                                            <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                            <td>{{ $item->stok }}</td>
                                            <td>
                                                <!-- Edit -->
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editRowModal{{ $item->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <!-- Modal Edit -->
                                                <div class="modal fade" id="editRowModal{{ $item->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form method="POST"
                                                                action="{{ route('admin.barang.update', $item->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-header border-0">
                                                                    <h5 class="modal-title">Edit Barang</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>Nama Barang</label>
                                                                        <input name="nama_barang" type="text"
                                                                            class="form-control"
                                                                            value="{{ $item->nama_barang }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Kode Barang</label>
                                                                        <input name="kode_barang" type="text"
                                                                            class="form-control"
                                                                            value="{{ $item->kode_barang }}" required readonly>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Kategori</label>
                                                                        <select name="kategori_id" class="form-control"
                                                                            required>
                                                                            @foreach($kategori as $kat)
                                                                               <option value="{{ $kat->id }}" {{ $item->kategori_id == $kat->id ? 'selected' : '' }}>
                                                                                    {{ $kat->nama_kategori }}
                                                                                </option>

                                                                                {{ $kat->nama_kategori }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="satuan" class="form-label">Satuan</label>
                                                                        <select name="satuan" class="form-control" required>
                                                                            <option value="pcs" {{ $item->satuan == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                                                            <option value="lembar" {{ $item->satuan == 'lembar' ? 'selected' : '' }}>Lembar</option>
                                                                            <option value="batang" {{ $item->satuan == 'batang' ? 'selected' : '' }}>Batang</option>
                                                                            <option value="sak" {{ $item->satuan == 'sak' ? 'selected' : '' }}>Sak</option>
                                                                            <option value="kg" {{ $item->satuan == 'kg' ? 'selected' : '' }}>Kilogram (Kg)</option>
                                                                            <option value="m" {{ $item->satuan == 'm' ? 'selected' : '' }}>Meter (m)</option>
                                                                            <option value="m2" {{ $item->satuan == 'm2' ? 'selected' : '' }}>Meter Persegi (m²)</option>
                                                                            <option value="liter" {{ $item->satuan == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                                                            <option value="box" {{ $item->satuan == 'box' ? 'selected' : '' }}>Box</option>
                                                                            <option value="pickup" {{ $item->satuan == 'pickup' ? 'selected' : '' }}>Pickup</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Harga Beli</label>
                                                                        <input name="harga_beli" type="text" class="form-control rupiah"
    value="{{ $item->harga_beli }}" required>

                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Harga Jual</label>
                                                                        <input name="harga_jual" type="text" class="form-control rupiah"
    value="{{ $item->harga_jual }}"> @error('harga_jual')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Stok</label>
                                                                        <input name="stok" type="number" class="form-control"
                                                                            value="{{ $item->stok }}" required>
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

                                                <!-- Hapus -->
                                                <form action="{{ route('admin.barang.destroy', $item->id) }}" method="POST"
                                                    style="display:inline;" id="delete-form-{{ $item->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmDelete({{ $item->id }})">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>

                                                <script>
                                                    function confirmDelete(itemId) {
                                                        Swal.fire({
                                                            title: 'Apakah Anda yakin?',
                                                            text: "Data ini akan dihapus secara permanen!",
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonText: 'Ya, hapus!',
                                                            cancelButtonText: 'Batal',
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                document.getElementById('delete-form-' + itemId).submit();
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

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $barang->links('pagination::bootstrap-5') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection