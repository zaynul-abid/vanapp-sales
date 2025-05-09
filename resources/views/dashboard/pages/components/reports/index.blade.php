@extends('dashboard.layouts.app')
@section('title', 'Reports Dashboard')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container py-5">
        <div class="dashboard-header">
            <h1>Reports Dashboard</h1>
            <p class="text-muted">Explore detailed insights on sales, stock, and more.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="report-card">
                    <div class="card-body">
                        <i class="fas fa-users icon customer-icon"></i>
                        <h5 class="card-title">Customer Report</h5>
                        <a href="{{ route('customer_report.index') }}" class="btn btn-primary">View Report</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="report-card">
                    <div class="card-body">
                        <i class="fas fa-truck icon van-icon"></i>
                        <h5 class="card-title">Van Report</h5>
                        <a href="{{ route('van_report.index') }}" class="btn btn-primary">View Report</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="report-card">
                    <div class="card-body">
                        <i class="fas fa-user-tie icon employee-icon"></i>
                        <h5 class="card-title">Employee Report</h5>
                        <a href="{{ route('employee_report.index') }}" class="btn btn-primary">View Report</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="report-card">
                    <div class="card-body">
                        <i class="fas fa-boxes icon stock-icon"></i>
                        <h5 class="card-title">Stock Report</h5>
                        <a href="{{ route('stock_report.index') }}" class="btn btn-primary">View Report</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="report-card">
                    <div class="card-body">
                        <i class="fas fa-chart-line icon sale-icon"></i>
                        <h5 class="card-title">Sale Report</h5>
                        <a href="{{ route('sale_report.index') }}" class="btn btn-primary">View Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endpush
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
        .report-card {
            background: #ffffff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .card-body {
            text-align: center;
            padding: 1.5rem;
        }
        .card-title {
            color: #333333;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        .btn-primary {
            border-radius: 5px;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            transition: background-color 0.3s ease;
        }
        .icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .customer-icon { color: #ff6b6b; }
        .van-icon { color: #4ecdc4; }
        .employee-icon { color: #feca57; }
        .stock-icon { color: #48dbfb; }
        .sale-icon { color: #ff9f43; }
    </style>

@endpush

