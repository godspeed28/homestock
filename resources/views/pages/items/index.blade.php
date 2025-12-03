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

    @if (session('error'))
        <div class="alert alert-danger shadow-sm">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="mb-4">
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
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-folder-plus mr-2"></i> Tambah Item Baru
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Nama Item -->
                        <div class="form-group">
                            <label>Nama Item</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Contoh: Gula Pasir" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror"
                                required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stok -->
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                placeholder="Masukkan jumlah stok" value="{{ old('stock') }}" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Satuan -->
                        <div class="form-group">
                            <label>Satuan</label>
                            <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror"
                                placeholder="Contoh: Kg, Pcs" value="{{ old('unit') }}" required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harga -->
                        <div class="form-group">
                            <label>Harga Satuan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="harga_satuan"
                                    class="form-control @error('harga_satuan') is-invalid @enderror"
                                    placeholder="Masukkan harga" value="{{ old('harga_satuan') }}" min="0" required>
                                @error('harga_satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Minimum Stock -->
                        <div class="form-group">
                            <label>Minimum Stock</label>
                            <input type="number" name="minimum_stock"
                                class="form-control @error('minimum_stock') is-invalid @enderror"
                                placeholder="Stok minimum sebelum peringatan" value="{{ old('minimum_stock') }}"
                                min="1" required>
                            @error('minimum_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
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

    <!-- Tabel -->
    <div class="card shadow">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table mr-2"></i> Daftar Item
            </h6>
        </div>

        <div class="card-body">
            @if ($items->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data item. Silakan tambah item baru.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center" id="dataTable" width="100%"
                        cellspacing="0">
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
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($items as $item)
                                @php
                                    $isCritical = $item->stock <= $item->minimum_stock;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="font-weight-bold">{{ $item->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $isCritical ? 'danger' : 'primary' }} p-2">
                                            {{ round($item->stock) }}
                                        </span>
                                    </td>
                                    <td>{{ $item->unit }}</td>
                                    <td>
                                        <span class="badge badge-warning p-2">{{ round($item->minimum_stock) }}</span>
                                    </td>
                                    <td>{{ rupiah($item->harga_satuan) }}</td>
                                    <td>{{ rupiah($item->total_harga) }}</td>
                                    <td>
                                        @if ($item->category)
                                            <span class="badge badge-info p-2">{{ $item->category->name }}</span>
                                        @else
                                            <span class="badge badge-secondary p-2">Tanpa Kategori</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($isCritical)
                                            <span class="badge badge-danger px-3 py-2">Kritis</span>
                                        @else
                                            <span class="badge badge-success px-3 py-2">Aman</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="#" class="btn btn-sm btn-circle btn-outline-warning mr-2"
                                                data-toggle="modal" data-target="#editItemModal{{ $item->id }}"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="#" class="btn btn-sm btn-circle btn-outline-danger btn-delete"
                                                data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>

                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('items.destroy', $item->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('items.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-content shadow">
                                                <div class="modal-header bg-warning text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-edit mr-2"></i> Edit Item
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal">
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
                                                            <option value="">Tanpa Kategori</option>
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
                                                            value="{{ round($item->stock) }}" min="0" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Satuan</label>
                                                        <input type="text" name="unit" class="form-control"
                                                            value="{{ $item->unit }}" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Harga Satuan</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="number" name="harga_satuan"
                                                                class="form-control" value="{{ $item->harga_satuan }}"
                                                                min="0" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Minimum Stock</label>
                                                        <input type="number" name="minimum_stock" class="form-control"
                                                            value="{{ round($item->minimum_stock) }}" min="1"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-dismiss="modal">
                                                        <i class="fas fa-times mr-1"></i> Batal
                                                    </button>
                                                    <button type="submit" class="btn btn-warning text-white shadow-sm">
                                                        <i class="fas fa-save mr-1"></i> Update
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
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // Inisialisasi tooltip
            $('[title]').tooltip();

            // SweetAlert untuk delete
            $('.btn-delete').click(function(e) {
                e.preventDefault();
                let itemId = $(this).data('id');
                let itemName = $(this).data('name');

                Swal.fire({
                    title: 'Hapus Item?',
                    html: `<strong>${itemName}</strong><br>Data tidak bisa dikembalikan setelah dihapus.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#delete-form-' + itemId).submit();
                    }
                });
            });
        });
    </script>
@endsection
