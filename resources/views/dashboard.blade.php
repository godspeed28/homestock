@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-chart-line mr-2"></i> Dashboard</h1>
    </div>

    <div class="row">

        <!-- Total Item -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 dashboard-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Item</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ totalItem() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Kritis -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 dashboard-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Item Kritis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ itemKritis() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-triangle-exclamation fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Terpakai -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 dashboard-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Item Terpakai</div>

                            @php
                                $presentase = 0;
                                if (totalItem() > 0) {
                                    $presentase = (itemTerpakai() / totalItem()) * 100;
                                    $presentase = min($presentase, 100);
                                }
                            @endphp

                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ itemTerpakai() }}</div>

                            <div class="progress progress-sm mt-2">
                                <div class="progress-bar bg-info" role="progressbar"
                                    style="width: {{ round($presentase) }}%;" aria-valuenow="{{ itemTerpakai() }}"
                                    aria-valuemin="0" aria-valuemax="{{ totalItem() }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-auto text-center">
                            <i class="fas fa-box-open fa-2x text-info"></i>
                            <div class="text-xs font-weight-bold mt-1 text-info">
                                {{ totalItem() > 0 ? round($presentase) . '%' : '0%' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Total Harga Item -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 dashboard-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">

                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Harga Item</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ rupiah(totalHargaItem()) }}</div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-warning"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4 dashboard-card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area mr-2"></i> Items Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4 dashboard-card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie mr-2"></i> Category Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="categoryPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small" id="categoryLegend"></div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('sbadmin/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('sbadmin/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('sbadmin/js/demo/chart-pie-demo.js') }}"></script>
@endsection
