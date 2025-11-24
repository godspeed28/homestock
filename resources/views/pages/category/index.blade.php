@extends('layouts.main')

@section('title', 'Inventory')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-warehouse mr-2"></i> Inventory Management</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <!-- Tambah Kategori -->
        <button type="button" class="btn btn-primary btn-icon-split shadow-sm" data-toggle="modal"
            data-target="#createKategoriModal">
            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
            <span class="text">Tambah Kategori</span>
        </button>

        <!-- Items -->
        <a href="{{ route('items.index') }}" class="btn btn-success btn-icon-split shadow-sm ml-2">
            <span class="icon text-white-50"><i class="fas fa-clipboard-list"></i></span>
            <span class="text">Item</span>
        </a>
    </div>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="createKategoriModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('category.store') }}" method="POST">
                @csrf
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-folder-plus mr-2"></i>Tambah Kategori</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Kategori</label>
                            <input type="text" name="name" class="form-control" id="name" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary shadow-sm">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <hr>

    <div class="card shadow mb-4 border-0">
        <div class="card-header py-3 bg-white border-bottom">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table mr-2"></i>Data Kategori
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-center table-bordered" id="dataTable">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Kategori</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge badge-info p-2">{{ $category->name }}</span>
                                </td>
                                <td>
                                    <!-- Edit -->
                                    <a href="#" data-toggle="modal"
                                        data-target="#editCategoryModal{{ $category->id }}">
                                        <i class="fas fa-edit text-warning mx-2" style="font-size: 1.1rem;"></i>
                                    </a>

                                    <!-- Delete -->
                                    <a href="#" class="btn-delete" data-id="{{ $category->id }}">
                                        <i class="fas fa-trash-alt text-danger mx-2" style="font-size: 1.1rem;"></i>
                                    </a>

                                    <!-- Form Delete -->
                                    <form id="delete-form-{{ $category->id }}"
                                        action="{{ route('category.destroy', $category->id) }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Modal Edit -->
                        @foreach ($categories as $category)
                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('category.update', $category->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-edit mr-2"></i>Edit Kategori
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nama Kategori</label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ $category->name }}" required>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light border"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit"
                                                    class="btn btn-warning text-white shadow-sm">Simpan</button>
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
                let categoryId = this.dataset.id;

                Swal.fire({
                    title: 'Hapus kategori?',
                    text: "Data yang dihapus tidak dapat dikembalikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + categoryId).submit();
                    }
                });
            });
        });
    </script>

@endsection
