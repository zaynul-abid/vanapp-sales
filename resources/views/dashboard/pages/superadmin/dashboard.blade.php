@extends('dashboard.layouts.app')
@section('title', 'Super-Admin Dashboard')

@section('navbar')
    @include('dashboard.partials.sidebar.superadmin-sidebar')
@endsection

@section('content')
    <div class="container py-5">
        <div class="dashboard-header">
            <h1>Superadmin Dashboard</h1>
            <p class="text-muted">Welcome, Superadmin! Monitor key sales metrics and trends below.</p>
        </div>

        <!-- Stats Section -->
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <i class="fas fa-dollar-sign icon sales-icon"></i>
                    <h5>Total Sales</h5>
                    <div class="count">{{ number_format($totalSales) }}</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <i class="fas fa-money-bill icon sales-icon"></i>
                    <h5>Net Total</h5>
                    <div class="count">{{ number_format($totalNet, 2) }}</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <i class="fas fa-boxes icon items-icon"></i>
                    <h5>Top Items Sold</h5>
                    <div class="count">{{ $topItems->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5>Sales Trend by Financial Year</h5>
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5>Top Selling Items</h5>
                    <canvas id="itemsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        body {
            background-color: #f8f9fa;
            color: #333333;
        }
        .dashboard-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: #ffffff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
            text-align: center;
            padding: 1.5rem;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .stat-card h5 {
            color: #333333;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .stat-card .count {
            font-size: 2rem;
            font-weight: bold;
        }
        .chart-card {
            background: #ffffff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            padding: 1.5rem;
        }
        .chart-card canvas {
            max-height: 300px;
        }
        .icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .sales-icon { color: #4ecdc4; }
        .items-icon { color: #feca57; }
    </style>
@endpush

@push('scripts')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                // Sales Trend Chart
                const salesCtx = document.getElementById('salesChart');
                if (!salesCtx) {
                    console.error('Sales chart canvas not found');
                    return;
                }
                new Chart(salesCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($years),
                        datasets: [{
                            label: 'Net Total',
                            data: @json($salesData),
                            borderColor: '#4ecdc4',
                            backgroundColor: 'rgba(78, 205, 196, 0.2)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });

                // Top Selling Items Chart
                const itemsCtx = document.getElementById('itemsChart');
                if (!itemsCtx) {
                    console.error('Items chart canvas not found');
                    return;
                }
                new Chart(itemsCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($topItemNames),
                        datasets: [{
                            label: 'Quantity Sold',
                            data: @json($topItemQuantities),
                            backgroundColor: '#feca57',
                            borderColor: '#feca57',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing charts:', error);
            }
        });
    </script>
@endpush
