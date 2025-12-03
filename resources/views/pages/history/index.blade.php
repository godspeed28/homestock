@extends('layouts.main')

@section('title', 'History')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-history mr-2"></i> Reports
        </h1>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="mb-3 d-flex">
        <a href="{{ route('history.add') }}" class="btn btn-success btn-icon-split mr-2">
            <span class="icon text-white-50">
                <i class="fas fa-box-open"></i>
            </span>
            <span class="text">Usage</span>
        </a>

        <button id="delete-selected" class="btn btn-danger btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-trash"></i></span>
            <span class="text">Hapus</span>
        </button>
    </div>

    <form id="delete-multiple-form" action="{{ route('history.destroyMultiple') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="ids" id="selected-ids">
    </form>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="card shadow border-0">
        <div class="card-body table-responsive">

            <table class="table table-striped table-bordered" id="dataTable" width="100%">
                <thead class="bg-primary text-white">
                    <tr>
                        <th width="30"><input type="checkbox" id="select-all"></th>
                        <th width="50">#</th>
                        <th>Item</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                        <th width="60">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($usages as $usage)
                        <tr>
                            <td><input type="checkbox" class="select-item" value="{{ $usage->id }}"></td>
                            <td>{{ $loop->iteration }}</td>
                            <td><i class="fas fa-cube text-primary mr-1"></i>{{ $usage->item->name }}</td>
                            <td>{{ $usage->description }}</td>
                            <td>
                                <span class="badge badge-light">
                                    {{ $usage->created_at->format('d M Y H:i') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn-delete" data-id="{{ $usage->id }}" title="Hapus Data">
                                    <i class="fa fa-trash text-danger"></i>
                                </a>

                                <form id="delete-form-{{ $usage->id }}"
                                    action="{{ route('history.destroy', $usage->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fa fa-info-circle mr-1"></i>
                                Belum ada riwayat penggunaan stok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Delete single
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let id = this.dataset.id;

                    Swal.fire({
                        title: 'Apakah kamu yakin?',
                        text: "Data akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + id).submit();
                        }
                    });
                });
            });

            // Checkbox logic
            const selectAll = document.getElementById('select-all');
            const items = document.querySelectorAll('.select-item');

            selectAll.addEventListener('change', () => {
                items.forEach(i => i.checked = selectAll.checked);
            });

            items.forEach(i => {
                i.addEventListener('change', () => {
                    selectAll.checked = [...items].every(cb => cb.checked);
                });
            });

            // Delete multiple
            document.getElementById('delete-selected').addEventListener('click', function() {
                let selected = [...document.querySelectorAll('.select-item:checked')].map(i => i.value);

                if (!selected.length) {
                    Swal.fire('Pilih minimal satu data!');
                    return;
                }

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Data terpilih akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        document.getElementById('selected-ids').value = selected.join(',');
                        document.getElementById('delete-multiple-form').submit();
                    }
                });
            });

            // Sweetalert success
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

        });
    </script>
@endsection
