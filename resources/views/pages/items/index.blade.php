@extends('layouts.main')

@section('title', 'Inventory')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-warehouse mr-2"></i> Inventory Management
        </h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <!-- Tombol Tambah Item -->
        <button type="button" class="btn btn-primary btn-icon-split shadow-sm" data-toggle="modal"
            data-target="#createItemModal">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Tambah Item</span>
        </button>

        <!-- Tombol ke kategori -->
        <a href="{{ route('category.index') }}" class="btn btn-success btn-icon-split shadow-sm ml-2">
            <span class="icon text-white-50">
                <i class="fas fa-tags"></i>
            </span>
            <span class="text">Kategori</span>
        </a>
    </div>

    <!-- Modal Tambah Item -->
    <div class="modal fade" id="createItemModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-box mr-2"></i> Tambah Item Baru</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Nama Item -->
                        <div class="form-group">
                            <label>Nama Item</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Gula Pasir"
                                required>
                        </div>

                        <!-- Kategori -->
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control" required>
                                <option disabled selected>Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stok -->
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" name="stock" class="form-control" placeholder="Masukkan jumlah stok"
                                required>
                        </div>

                        <!-- Satuan -->
                        <div class="form-group">
                            <label>Satuan</label>
                            <input type="text" name="unit" class="form-control" placeholder="Contoh: Kg, Pcs"
                                required>
                        </div>

                        <!-- Harga -->
                        <div class="form-group">
                            <label>Harga Satuan</label>
                            <input type="number" name="harga_satuan" class="form-control" placeholder="Masukkan harga"
                                required>
                        </div>

                        <!-- Minimum Stock -->
                        <div class="form-group">
                            <label>Minimum Stock</label>
                            <input type="number" name="minimum_stock" class="form-control"
                                placeholder="Stok minimum sebelum peringatan" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <hr>

    <!-- Tabel -->
    <div class="card shadow">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table mr-2"></i> Data Item
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center" id="dataTable">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Stock</th>
                            <th>Satuan</th>
                            <th>Minimum</th>
                            <th>Harga Satuan</th>
                            <th>Total Harga</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-weight-bold">{{ $item->name }}</td>
                                <td>
                                    <span class="badge badge-primary p-2">{{ round($item->stock) }}</span>
                                </td>
                                <td>{{ $item->unit }}</td>
                                <td>
                                    <span class="badge badge-warning p-2">{{ round($item->minimum_stock) }}</span>
                                </td>
                                <td>{{ rupiah($item->harga_satuan) }}</td>
                                <td>{{ rupiah($item->total_harga) }}</td>
                                <td>
                                    @if (!empty($item->category->name))
                                        <span class="badge badge-info p-2">{{ $item->category->name }}</span>
                                    @else
                                        <span class="badge badge-secondary p-2">Tidak ada kategori</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="mr-2" data-toggle="modal"
                                        data-target="#editItemModal{{ $item->id }}" data-toggle="tooltip"
                                        title="Edit">
                                        <i class="fa fa-edit text-warning"></i>
                                    </a>

                                    <a href="#" class="btn-delete" data-id="{{ $item->id }}"
                                        data-toggle="tooltip" title="Hapus">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>

                                    <form id="delete-form-{{ $item->id }}"
                                        action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editItemModal{{ $item->id }}">
                                <div class="modal-dialog">
                                    <form action="{{ route('items.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-content shadow">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-edit mr-2"></i> Edit Item
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nama Item</label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ $item->name }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Kategori</label>
                                                    <select name="category_id" class="form-control">
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ $category->id == $item->category_id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Stok</label>
                                                    <input type="number" name="stock" class="form-control"
                                                        value="{{ round($item->stock) }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Satuan</label>
                                                    <input type="text" name="unit" class="form-control"
                                                        value="{{ $item->unit }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Harga Satuan</label>
                                                    <input type="number" name="harga_satuan" class="form-control"
                                                        value="{{ $item->harga_satuan }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Minimum Stock</label>
                                                    <input type="number" name="minimum_stock" class="form-control"
                                                        value="{{ round($item->minimum_stock) }}" required>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-dismiss="modal">
                                                    Batal
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let itemId = this.dataset.id;

                Swal.fire({
                    title: 'Hapus Item?',
                    text: "Data tidak bisa dikembalikan setelah dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + itemId).submit();
                    }
                });
            });
        });
    </script>
@endsection
