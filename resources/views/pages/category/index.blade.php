@extends('layouts.main')

@section('title', 'Kategori')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tags mr-2"></i> Category Management
        </h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="mb-4">
        <!-- Tambah Kategori -->
        <button type="button" class="btn btn-primary btn-icon-split shadow-sm" data-toggle="modal"
            data-target="#createCategoryModal">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Tambah Kategori</span>
        </button>

        <!-- Items -->
        <a href="{{ route('items.index') }}" class="btn btn-success btn-icon-split shadow-sm ml-2">
            <span class="icon text-white-50">
                <i class="fas fa-clipboard-list"></i>
            </span>
            <span class="text">Item</span>
        </a>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="btn btn-info btn-icon-split shadow-sm ml-2">
            <span class="icon text-white-50">
                <i class="fas fa-chart-line"></i>
            </span>
            <span class="text">Dashboard</span>
        </a>
    </div>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('category.store') }}" method="POST">
                @csrf
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-folder-plus mr-2"></i> Tambah Kategori Baru
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Kategori</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="name" value="{{ old('name') }}" placeholder="Contoh: Elektronik, Bahan Makanan"
                                required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle mr-1"></i>
                            Kategori digunakan untuk mengelompokkan item inventory.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow border-0">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table mr-2"></i> Daftar Kategori
            </h6>
        </div>

        <div class="card-body">
            @if ($categories->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Belum ada kategori.</p>
                    <p class="text-muted">Klik "Tambah Kategori" untuk menambahkan.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="categoryTable" width="100%"
                        cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">#</th>
                                <th class="text-center">Nama Kategori</th>
                                <th width="20%" class="text-center">Jumlah Item</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info px-3 py-2 font-weight-normal">
                                            <i class="fas fa-tag mr-1"></i> {{ $category->name }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-secondary px-3 py-2">
                                            {{ $category->items_count ?? 0 }} Item
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-sm btn-circle btn-outline-warning mr-2"
                                                data-toggle="modal" data-target="#editCategoryModal{{ $category->id }}"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <button type="button"
                                                class="btn btn-sm btn-circle btn-outline-danger btn-delete"
                                                data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <!-- Delete Form -->
                                            <form id="delete-form-{{ $category->id }}"
                                                action="{{ route('category.destroy', $category->id) }}" method="POST"
                                                class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Edit (Outside table) -->
    @foreach ($categories as $category)
        <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('category.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content shadow-lg">
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-edit mr-2"></i> Edit Kategori
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Kategori</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $category->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($category->items_count > 0)
                                <div class="alert alert-warning small">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Kategori ini digunakan oleh {{ $category->items_count }} item.
                                </div>
                            @endif
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
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

@endsection

@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Inisialisasi DataTable
        $(document).ready(function() {
            $('#categoryTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "order": [
                    [1, 'asc']
                ],
                "pageLength": 10,
                "responsive": true,
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 3]
                }]
            });

            // Tooltip
            $('[title]').tooltip();

            // SweetAlert untuk delete
            $('.btn-delete').click(function(e) {
                e.preventDefault();
                let categoryId = $(this).data('id');
                let categoryName = $(this).data('name');

                Swal.fire({
                    title: 'Hapus Kategori?',
                    html: `<div class="text-center">
                            <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                            <p><strong>"${categoryName}"</strong></p>
                            <p class="text-muted">Semua item dalam kategori ini akan kehilangan kategori.</p>
                            <p>Data tidak dapat dikembalikan setelah dihapus.</p>
                           </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash mr-2"></i> Ya, Hapus!',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i> Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#delete-form-' + categoryId).submit();
                    }
                });
            });
        });
    </script>
@endsection
