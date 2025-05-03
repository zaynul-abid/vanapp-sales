@extends('dashboard.layouts.app')
@section('title', 'Alternative Units')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Alternative Units</h4>
            <a href="{{ route('alternative-units.create') }}" class="btn btn-dark">
                <i class="ti ti-plus me-1"></i> Add Alternative Unit
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($alternativeUnits as $key => $alterunit)
                    <tr>
                        <td>{{ ($alternativeUnits->currentPage() - 1) * $alternativeUnits->perPage() + $loop->iteration }}</td>
                        <td>{{ $alterunit->name }}</td>
                        <td>{{ $alterunit->description ? :'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $alterunit->status ? 'success' : 'danger' }}">
                                {{ $alterunit->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('alternative-units.edit', $alterunit) }}" class="btn btn-sm btn-warning">
                                <i class="ti ti-edit"></i> Edit
                            </a>
                            <form action="{{ route('alternative-units.destroy', $alterunit) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this alternative unit?')">
                                    <i class="ti ti-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No alternative units found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $alternativeUnits->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
