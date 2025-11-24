@extends('layouts.main')

@section('title', 'Usage')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-boxes mr-2"></i> Inventory Management
        </h1>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4 border-0">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-hand-holding-box mr-2"></i> Use Item
                    </h6>
                </div>

                <div class="card-body">
                    <div class="row">

                        {{-- Jika item kosong --}}
                        @if ($items->isEmpty())
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada item yang tersedia.</p>
                            </div>
                        @endif

                        {{-- Loop item --}}
                        @foreach ($items as $item)
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2 rounded">

                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">

                                            {{-- Detail Item --}}
                                            <div class="col mr-2">

                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                                                    <i class="fas fa-cube mr-1"></i> {{ $item->name }}
                                                </div>

                                                {{-- Info Stock --}}
                                                @php
                                                    $isCritical = $item->stock <= $item->minimum_stock;
                                                    $isEmpty = $item->stock == 0;
                                                @endphp

                                                <div class="font-weight-bold mb-2">

                                                    @if ($isEmpty)
                                                        <span class="badge badge-danger p-2">Stock: 0</span>
                                                    @elseif ($isCritical)
                                                        <span class="badge badge-warning p-2">
                                                            Stock: {{ round($item->stock) }} {{ $item->unit }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-primary p-2">
                                                            Stock: {{ round($item->stock) }} {{ $item->unit }}
                                                        </span>
                                                    @endif
                                                </div>

                                                {{-- Label kondisi --}}
                                                @if ($isCritical && !$isEmpty)
                                                    <p class="text-danger mb-1" style="font-size: 12px;">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i> Stock Kritis
                                                    </p>
                                                @endif

                                                @if ($isEmpty)
                                                    <p class="text-danger mb-1" style="font-size: 12px;">
                                                        <i class="fas fa-times-circle mr-1"></i> Stock Habis
                                                    </p>
                                                @endif
                                            </div>

                                            {{-- Button Ambil --}}
                                            <div class="col-auto text-center">

                                                <form action="{{ route('usage.ambil', $item->id) }}" method="POST">
                                                    @csrf

                                                    <button type="submit" class="btn btn-light border shadow-sm">
                                                        <i class="fas fa-hand-paper fa-2x text-primary"></i>
                                                        <div class="mt-1 font-weight-bold text-primary">
                                                            Ambil
                                                        </div>
                                                    </button>
                                                </form>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
