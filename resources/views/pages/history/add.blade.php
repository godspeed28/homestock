@extends('layouts.main')

@section('title', 'History Addition')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-history mr-2"></i> Reports
        </h1>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="d-flex mb-3">
        <a href="{{ route('history.index') }}" class="btn btn-success btn-icon-split mr-2">
            <span class="icon text-white-50">
                <i class="fas fa-plus-circle"></i>
            </span>
            <span class="text">Addition</span>
        </a>

        <button id="delete-selected" class="btn btn-danger btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-trash"></i>
            </span>
            <span class="text">Hapus</span>
        </button>
    </div>

    <form id="delete-multiple-form" action="{{ route('history.deleteMultiple') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="ids" id="selected-ids">
    </form>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped align-middle" id="dataTable">
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
                    @forelse($items as $item)
                        <tr>
                            <td><input type="checkbox" class="select-item" value="{{ $item->id }}"></td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <i class="fas fa-cube text-primary mr-1"></i>
                                {{ $item->item->name }}
                            </td>
                            <td>{{ $item->description }}</td>
                            <td>
                                <span class="badge badge-light">
                                    {{ $item->created_at->format('d M Y H:i') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn-delete" data-id="{{ $item->id }}" title="Hapus">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>

                                <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('history.delete', $item->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Belum ada riwayat penambahan stock.
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

            // Handle delete single
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.onclick = function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;

                    Swal.fire({
                        title: 'Hapus data ini?',
                        text: "Data akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then(result => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + id).submit();
                        }
                    });
                };
            });

            // Select all checkbox
            const selectAll = document.getElementById('select-all');
            const items = document.querySelectorAll('.select-item');

            selectAll.onchange = () => {
                items.forEach(i => i.checked = selectAll.checked);
            };

            items.forEach(cb => {
                cb.onchange = () => {
                    selectAll.checked = [...items].every(i => i.checked);
                };
            });

            // Delete multiple
            document.getElementById('delete-selected').onclick = function() {
                const selected = [...document.querySelectorAll('.select-item:checked')].map(i => i.value);

                if (!selected.length) {
                    Swal.fire('Pilih minimal satu data!');
                    return;
                }

                Swal.fire({
                    title: 'Hapus data terpilih?',
                    text: "Semua data yang dipilih akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        document.getElementById('selected-ids').value = selected.join(',');
                        document.getElementById('delete-multiple-form').submit();
                    }
                });
            };

            // Sweetalert Success
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 1800,
                    showConfirmButton: false
                });
            @endif

        });
    </script>
@endsection
