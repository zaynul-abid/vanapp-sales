@extends('dashboard.layouts.app')
@section('title', 'Van Index')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <h1 class="h4 fw-bold text-dark mb-0">Vans Management</h1>
            <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
                <input type="search" class="form-control form-control-sm search-box" placeholder="Search vans..." id="searchInput">
                <a href="{{ route('vans.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add Van
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="vansTable">
                        <thead class="table-light">
                        <tr>
                            <th class="ps-3">No.</th>
                            <th>Name</th>
                            <th class="d-none d-lg-table-cell">Register Number</th>
                            <th class="d-none d-md-table-cell">Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($vans as $index => $van)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex flex-column">
                                        <span>{{ $index + 1 }}</span>
                                        <small class="text-muted d-lg-none">{{ $van->register_number }}</small>
                                    </div>
                                </td>
                                <td>{{ $van->name }}</td>
                                <td class="d-none d-lg-table-cell">{{ $van->register_number }}</td>
                                <td class="d-none d-md-table-cell">
                                    <span class="badge {{ $van->status ? 'bg-success' : 'bg-secondary' }} text-white px-2 py-1">
                                        {{ $van->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('vans.show', $van->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('vans.edit', $van->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('vans.destroy', $van->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this van?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-info-circle me-2"></i> No vans found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $vans->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const input = this.value.toLowerCase();
            const rows = document.querySelectorAll('#vansTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        });
    </script>

    <style>
        .container {
            max-width: 1200px;
        }

        h1.h4 {
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .table {
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            background-color: #f8f9fa;
            padding: 0.75rem;
        }

        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-color: #f0f0f0;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.3s ease;
        }

        .table-responsive {
            margin-left: 0.5rem;
            margin-right: 0.5rem;
        }

        #vansTable {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .badge {
            font-size: 0.65rem;
            font-weight: 500;
        }

        .btn-sm {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }

        .btn-outline-info, .btn-outline-primary, .btn-outline-danger {
            transition: all 0.3s ease;
        }

        .btn-outline-info:hover {
            background-color: #0dcaf0;
            color: white;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: white;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        .alert-success {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .pagination {
            font-size: 0.85rem;
        }

        .pagination .page-link {
            border-radius: 4px;
            margin: 0 2px;
            color: #0d6efd;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        /* Adjust search box size */
        .search-box {
            width: 150px; /* Compact size for desktop */
            max-width: 100%; /* Ensure it doesn't overflow */
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .d-flex.flex-md-row {
                flex-direction: column !important;
                align-items: stretch !important;
            }

            .search-box {
                width: 100%; /* Full width on tablet */
                font-size: 0.85rem;
                padding: 0.4rem 0.75rem;
            }

            .btn-primary.btn-sm {
                width: 100%;
                font-size: 0.85rem;
                padding: 0.4rem 0.75rem;
            }

            .table th, .table td {
                padding: 0.5rem;
            }

            .badge {
                font-size: 0.6rem;
            }

            .btn-sm {
                font-size: 0.7rem;
                padding: 0.15rem 0.4rem;
            }
        }

        @media (max-width: 767px) {
            h1.h4 {
                font-size: 1.25rem;
            }

            .search-box {
                width: 100%; /* Full width on mobile */
                font-size: 0.8rem;
                padding: 0.35rem 0.65rem;
            }

            .table th, .table td {
                font-size: 0.8rem;
                padding: 0.4rem;
            }

            .btn-sm {
                font-size: 0.65rem;
                padding: 0.1rem 0.3rem;
            }

            .pagination {
                font-size: 0.8rem;
            }
        }
    </style>
@endsection
