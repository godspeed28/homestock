@extends('layouts.main')

@section('title', 'Usage - Inventory Management')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-hand-holding-box mr-2 text-primary"></i> Penggunaan Barang
            </h1>
            <p class="text-muted mb-0">Ambil barang yang tersedia dari inventaris</p>
        </div>
        <div class="d-flex align-items-center">
            <div class="mr-3">
                <div class="d-flex align-items-center">
                    <div class="mr-2">
                        <span class="badge badge-dot badge-success"></span>
                    </div>
                    <small class="text-muted">Stok Aman</small>
                </div>
                <div class="d-flex align-items-center mt-1">
                    <div class="mr-2">
                        <span class="badge badge-dot badge-warning"></span>
                    </div>
                    <small class="text-muted">Stok Kritis</small>
                </div>
                <div class="d-flex align-items-center mt-1">
                    <div class="mr-2">
                        <span class="badge badge-dot badge-danger"></span>
                    </div>
                    <small class="text-muted">Stok Habis</small>
                </div>
            </div>
            <button class="btn btn-outline-primary" id="toggleView">
                <i class="fas fa-th-large"></i>
            </button>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <div class="alert-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-3">
                    {{ session('success') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="ml-3">
                    {{ session('error') }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($items->isEmpty())
        <div class="text-center py-5">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-box-open fa-4x text-muted"></i>
                </div>
                <h3 class="mt-4 text-muted">Tidak ada barang tersedia</h3>
                <p class="text-muted mb-4">Tambahkan barang terlebih dahulu untuk mulai mengelola stok</p>
                <a href="{{ route('items.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Tambah Barang Baru
                </a>
            </div>
        </div>
    @else
        {{-- Filter Section --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                            </div>
                            <input type="text" id="searchBar" class="form-control border-left-0"
                                placeholder="Cari barang...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="stockFilter" class="form-control">
                            <option value="all">Semua Stok</option>
                            <option value="safe">Stok Aman</option>
                            <option value="critical">Stok Kritis</option>
                            <option value="empty">Stok Habis</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Items Grid --}}
        <div class="row" id="itemsGrid">
            @foreach ($items as $item)
                @php
                    $isCritical = $item->stock <= $item->minimum_stock;
                    $isEmpty = $item->stock == 0;
                    $isSafe = !$isEmpty && !$isCritical;
                    $stockPercentage = $item->maximum_stock > 0 ? ($item->stock / $item->maximum_stock) * 100 : 0;
                    $statusColor = $isEmpty ? 'danger' : ($isCritical ? 'warning' : 'success');
                    $statusText = $isEmpty ? 'Habis' : ($isCritical ? 'Kritis' : 'Aman');
                    $statusIcon = $isEmpty ? 'ban' : ($isCritical ? 'exclamation-triangle' : 'check-circle');
                @endphp

                <div class="col-xl-3 col-lg-4 col-md-6 mb-4 item-card"
                    data-status="{{ $isEmpty ? 'empty' : ($isCritical ? 'critical' : 'safe') }}"
                    data-name="{{ strtolower($item->name) }}">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                        <div class="card-body p-4">
                            {{-- Header --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1 mr-3">
                                    <h6 class="font-weight-bold text-dark mb-1 text-truncate" title="{{ $item->name }}">
                                        {{ $item->name }}
                                    </h6>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-pill badge-{{ $statusColor }} px-3 py-2">
                                        <i class="fas fa-{{ $statusIcon }} mr-1"></i> {{ $statusText }}
                                    </span>
                                </div>
                            </div>

                            {{-- Stock Info --}}
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Stok Tersedia</span>
                                    <span class="font-weight-bold text-{{ $statusColor }}">
                                        {{ $item->stock }} {{ $item->unit }}
                                    </span>
                                </div>

                                @if ($item->maximum_stock > 0)
                                    <div class="progress-stock mb-2">
                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                            <span>Kapasitas</span>
                                            <span>{{ round($stockPercentage, 1) }}%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-gradient-{{ $statusColor }}" role="progressbar"
                                                style="width: {{ min($stockPercentage, 100) }}%"
                                                aria-valuenow="{{ $stockPercentage }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="text-right small text-muted mt-1">
                                            {{ $item->stock }} / {{ $item->maximum_stock }} {{ $item->unit }}
                                        </div>
                                    </div>
                                @endif

                                @if ($isCritical && !$isEmpty)
                                    <div class="alert alert-warning small py-2 mb-0">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Minimum: {{ $item->minimum_stock }} {{ $item->unit }}
                                    </div>
                                @endif
                            </div>

                            {{-- Action Button --}}
                            <div class="mt-4 pt-3 border-top">
                                <form action="{{ route('usage.ambil', $item->id) }}" method="POST" class="usage-form">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-{{ $isEmpty ? 'outline-secondary' : 'primary' }} btn-block btn-lg py-3"
                                        {{ $isEmpty ? 'disabled' : '' }} data-item="{{ $item->name }}"
                                        data-unit="{{ $item->unit }}">
                                        <div class="d-flex align-items-center justify-content-center">
                                            @if ($isEmpty)
                                                <i class="fas fa-times-circle mr-2"></i>
                                                <span>Stok Habis</span>
                                            @else
                                                <i class="fas fa-hand-holding mr-2"></i>
                                                <div class="text-left">
                                                    <div class="font-weight-bold">Ambil Barang</div>
                                                    <small class="d-block">1 {{ $item->unit }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </button>
                                    @if (!$isEmpty)
                                        <p class="text-center text-muted small mt-2 mb-0">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Stok akan berkurang otomatis
                                        </p>
                                    @endif
                                </form>
                            </div>
                        </div>

                        {{-- Card Footer --}}
                        <div class="card-footer bg-transparent border-top-0 pt-0">
                            <div class="d-flex justify-content-between small text-muted">
                                <div>
                                    <i class="fas fa-history mr-1"></i>
                                    <span>Terakhir update: {{ $item->updated_at->diffForHumans() }}</span>
                                </div>
                                @if (!$isEmpty)
                                    <div class="text-{{ $statusColor }}">
                                        <i class="fas fa-circle mr-1"></i>
                                        {{ $statusText }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Empty Search State --}}
        <div id="noResults" class="text-center py-5 d-none">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-search fa-4x text-muted"></i>
                </div>
                <h3 class="mt-4 text-muted">Barang tidak ditemukan</h3>
                <p class="text-muted">Coba dengan kata kunci yang berbeda</p>
                <button class="btn btn-outline-primary" id="resetSearch">
                    <i class="fas fa-redo mr-2"></i>Reset Pencarian
                </button>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .hover-shadow {
            transition: all 0.3s ease;
            border: 1px solid #e3e6f0;
        }

        .hover-shadow:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1) !important;
            border-color: #4e73df;
        }

        .badge-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 4px;
        }

        .badge-dot.badge-success {
            background-color: #1cc88a;
        }

        .badge-dot.badge-warning {
            background-color: #f6c23e;
        }

        .badge-dot.badge-danger {
            background-color: #e74a3b;
        }

        .bg-gradient-success {
            background: linear-gradient(90deg, #1cc88a 0%, #13855c 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(90deg, #f6c23e 0%, #dda20a 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(90deg, #e74a3b 0%, #be2617 100%);
        }

        .progress {
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 10px;
        }

        .empty-state {
            max-width: 400px;
            margin: 0 auto;
        }

        .empty-state-icon {
            opacity: 0.6;
        }

        .alert .alert-icon {
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .btn-lg {
            border-radius: 8px;
            font-size: 1rem;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .card-footer {
            background: rgba(0, 0, 0, 0.02);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Auto dismiss alerts
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);

            // SweetAlert2 confirmation for item usage
            $('.usage-form').on('submit', function(e) {
                e.preventDefault();

                let form = this;
                let itemName = $(this).find('button').data('item');
                let unit = $(this).find('button').data('unit');

                Swal.fire({
                    title: 'Konfirmasi Pengambilan Barang',
                    html: `
                        <div class="text-center">
                            <i class="fas fa-hand-holding-box fa-3x text-primary mb-3"></i>
                            <p class="mb-2">Anda akan mengambil:</p>
                            <h5 class="text-primary mb-3">1 ${unit} ${itemName}</h5>
                            <p class="text-muted small">Stok akan otomatis berkurang setelah konfirmasi</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-check mr-2"></i>Ya, Ambil',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-primary btn-lg px-4 py-2',
                        cancelButton: 'btn btn-outline-secondary btn-lg px-4 py-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        $(form).find('button').html(`
                            <div class="spinner-border spinner-border-sm mr-2" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Memproses...
                        `).prop('disabled', true);

                        form.submit();
                    }
                });
            });

            // Search functionality
            $('#searchBar').on('keyup', function() {
                let searchTerm = $(this).val().toLowerCase();
                let hasVisibleItems = false;

                $('.item-card').each(function() {
                    let itemName = $(this).data('name');
                    let itemCode = $(this).find('.text-muted').text().toLowerCase();

                    if (itemName.includes(searchTerm) || itemCode.includes(searchTerm)) {
                        $(this).removeClass('d-none');
                        hasVisibleItems = true;
                    } else {
                        $(this).addClass('d-none');
                    }
                });

                // Show/hide no results message
                if (hasVisibleItems) {
                    $('#noResults').addClass('d-none');
                } else {
                    $('#noResults').removeClass('d-none');
                }
            });

            // Stock filter functionality
            $('#stockFilter').on('change', function() {
                let filter = $(this).val();
                let hasVisibleItems = false;

                $('.item-card').each(function() {
                    let status = $(this).data('status');

                    if (filter === 'all' || filter === status) {
                        $(this).removeClass('d-none');
                        hasVisibleItems = true;
                    } else {
                        $(this).addClass('d-none');
                    }
                });

                // Show/hide no results message
                if (hasVisibleItems) {
                    $('#noResults').addClass('d-none');
                } else {
                    $('#noResults').removeClass('d-none');
                }
            });

            // Reset search
            $('#resetSearch').on('click', function() {
                $('#searchBar').val('');
                $('#stockFilter').val('all');
                $('.item-card').removeClass('d-none');
                $('#noResults').addClass('d-none');
            });

            // Toggle grid/list view (placeholder)
            $('#toggleView').on('click', function() {
                let icon = $(this).find('i');
                if (icon.hasClass('fa-th-large')) {
                    icon.removeClass('fa-th-large').addClass('fa-list');
                    $('#itemsGrid').addClass('list-view');
                    $(this).addClass('btn-primary').removeClass('btn-outline-primary');
                } else {
                    icon.removeClass('fa-list').addClass('fa-th-large');
                    $('#itemsGrid').removeClass('list-view');
                    $(this).removeClass('btn-primary').addClass('btn-outline-primary');
                }
            });

            // Add subtle animation to cards on page load
            $('.item-card').each(function(index) {
                $(this).css({
                    'opacity': '0',
                    'transform': 'translateY(20px)'
                }).delay(index * 100).animate({
                    'opacity': '1',
                    'transform': 'translateY(0)'
                }, 500);
            });
        });
    </script>
@endpush
