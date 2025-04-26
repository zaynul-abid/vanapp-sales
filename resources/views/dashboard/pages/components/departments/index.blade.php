@extends('dashboard.layouts.app')
@section('title', 'Departments')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h1 class="card-title mb-0">Departments</h1>
                <a href="{{ route('departments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Department
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
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
                        @forelse($departments as $department)
                            <tr>
                                <td>{{ ($departments->currentPage() - 1) * $departments->perPage() + $loop->iteration }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ Str::limit($department->description, 50) }}</td>
                                <td>
                                <span class="badge bg-{{ $department->status ? 'success' : 'danger' }}">
                                    {{ $department->status ? 'Active' : 'Inactive' }}
                                </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('departments.show', $department->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No departments found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $departments->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
