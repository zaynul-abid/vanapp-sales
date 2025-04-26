```blade
@extends('dashboard.layouts.app')

@section('title', 'Van Assignment')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <style>
        /* Base styles */
        .container {
            padding: 2rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .flex-container {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -1rem;
        }

        .full-width {
            flex: 0 0 100%;
            padding: 0 1rem;
        }

        .card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin: 1.5rem 0;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(to right, #3b5998, #4682b4);
            padding: 1.5rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .card-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .card-title i {
            margin-right: 0.5rem;
        }

        .success-message {
            margin: 1rem;
            padding: 1rem;
            background: #e6f4ea;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .success-message i {
            color: #2e7d32;
            margin-right: 0.5rem;
        }

        .success-message button {
            margin-left: auto;
            background: none;
            border: none;
            color: #2e7d32;
            cursor: pointer;
        }

        .card-body {
            padding: 1.5rem;
        }

        .inner-flex {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -1rem;
        }

        /* Van List Section */
        .van-list {
            flex: 0 0 66.666%;
            padding: 0 1rem;
            margin-bottom: 2rem;
        }

        .van-card {
            background: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .van-card-header {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .van-card-title {
            color: #1a1a1a;
            font-size: 1.25rem;
            font-weight: 500;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .van-card-title i {
            color: #3b5998;
            margin-right: 0.5rem;
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .table thead {
            background: #f5f5f5;
        }

        .table th {
            padding: 1rem;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4a4a4a;
        }

        .table th.center {
            text-align: center;
        }

        .table tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: background 0.2s;
        }

        .table td {
            padding: 1rem;
        }

        .table td.center {
            text-align: center;
        }

        .van-details h6 {
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
            color: #1a1a1a;
        }

        .van-details p {
            margin: 0;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .status-badge {
            display: inline-flex;
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 1rem;
        }

        .status-available {
            background: #e6f4ea;
            color: #2e7d32;
        }

        .status-unavailable {
            background: #ffebee;
            color: #c62828;
        }

        .employee-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .employee-info span {
            font-weight: 500;
            color: #1a1a1a;
        }

        .employee-info small {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .unassigned {
            color: #6b7280;
        }

        .action-button {
            background: none;
            border: none;
            color: #c62828;
            cursor: pointer;
        }

        /* Assignment Form Section */
        .form-section {
            flex: 0 0 33.333%;
            padding: 0 1rem;
            margin-bottom: 2rem;
        }

        .form-card {
            background: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .form-card-header {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .form-card-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4a4a4a;
            margin-bottom: 0.5rem;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: #1a1a1a;
            background: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .form-group select option[disabled] {
            color: #b0b0b0;
        }

        .form-group p {
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: #6b7280;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .submit-button {
            width: 100%;
            padding: 0.75rem;
            background: #3b5998;
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .submit-button i {
            margin-right: 0.5rem;
        }

        .submit-button:hover {
            background: #2a4373;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .van-list, .form-section {
                flex: 0 0 100%;
            }

            .van-list {
                margin-bottom: 1.5rem;
            }

            .card-title {
                font-size: 1.25rem;
            }

            .van-card-title, .form-card-title {
                font-size: 1.1rem;
            }

            .table th, .table td {
                font-size: 0.75rem;
                padding: 0.75rem;
            }

            .form-group select, .submit-button {
                font-size: 0.875rem;
                padding: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem 0.5rem;
            }

            .card {
                margin: 1rem 0;
            }

            .card-header {
                padding: 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            .table-container {
                width: 100%;
                overflow-x: auto;
            }

            .table {
                min-width: 600px; /* Ensures table scrolls horizontally */
            }

            .form-card-body {
                padding: 1rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 576px) {
            .card-title {
                font-size: 1rem;
            }

            .van-card-title, .form-card-title {
                font-size: 1rem;
            }

            .success-message {
                font-size: 0.75rem;
                padding: 0.75rem;
            }

            .submit-button {
                font-size: 0.875rem;
            }
        }
    </style>

    <div class="container">
        <div class="flex-container">
            <div class="full-width">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-van-shuttle"></i> Van Assignment Management
                        </h4>
                    </div>

                    @if(session('success'))
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                            <button type="button" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="inner-flex">
                            <!-- Van List Section -->
                            <div class="van-list">
                                <div class="van-card">
                                    <div class="van-card-header">
                                        <h5 class="van-card-title">
                                            <i class="fas fa-van-shuttle"></i> Van Inventory
                                        </h5>
                                    </div>
                                    <div class="table-container">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Van Details</th>
                                                <th>Status</th>
                                                <th class="center">Assigned To</th>
                                                <th class="center">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($vans as $van)
                                                <tr>
                                                    <td>
                                                        <div class="van-details">
                                                            <h6>{{ $van->name }}</h6>
                                                            <p>{{ $van->register_number }}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                            <span class="status-badge {{ $van->status ? 'status-available' : 'status-unavailable' }}">
                                                                {{ $van->status ? 'Available' : 'Not Available' }}
                                                            </span>
                                                    </td>
                                                    <td class="center">
                                                        @if($van->employee)
                                                            <div class="employee-info">
                                                                <span>{{ $van->employee->name }}</span>
                                                                <small>{{ $van->employee->position }}</small>
                                                            </div>
                                                        @else
                                                            <span class="unassigned">Not assigned</span>
                                                        @endif
                                                    </td>
                                                    <td class="center">
                                                        @if($van->employee)
                                                            <form action="{{ route('vans.unassign', $van->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('POST')
                                                                <button type="submit" class="action-button" data-bs-toggle="tooltip" data-bs-title="Unassign">
                                                                    <i class="fas fa-user-slash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Assignment Form Section -->
                            <div class="form-section">
                                <div class="form-card">
                                    <div class="form-card-header">
                                        <h5 class="form-card-title">
                                            <i class="fas fa-tasks"></i> Assign Vehicle
                                        </h5>
                                    </div>
                                    <div class="form-card-body">
                                        <form action="{{ route('vans.assign') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="van_id">Select Vehicle</label>
                                                <select id="van_id" name="van_id" required>
                                                    <option value="">-- Choose Vehicle --</option>
                                                    @foreach($vans as $van)
                                                        <option value="{{ $van->id }}" {{ $van->status ? '' : 'disabled' }}>
                                                            {{ $van->register_number }} ({{ $van->name }}) - {{ $van->status ? 'Available' : 'Unavailable' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p>Only available vehicles can be assigned</p>
                                            </div>

                                            <div class="form-group">
                                                <label for="employee_id">Select Employee</label>
                                                <select id="employee_id" name="employee_id" required>
                                                    <option value="">-- Choose Employee --</option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->position }})</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <button type="submit" class="submit-button">
                                                <i class="fas fa-link"></i> Assign Vehicle
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enable tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Disable unavailable vans in the dropdown
            const vanSelect = document.getElementById('van_id');
            vanSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.disabled) {
                    this.selectedIndex = 0;
                    alert('This vehicle is currently unavailable for assignment.');
                }
            });
        });
    </script>
@endsection
```
