@extends('dashboard.layouts.app')
@section('title', 'Employee Details')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container py-4">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h1 class="card-title h4 mb-0">Employee Details</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary btn-sm" title="Edit Employee">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Employee">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <dl class="row g-4">
                    <!-- Name -->
                    <div class="col-md-6">
                        <dt class="fw-bold">Name</dt>
                        <dd>{{ $employee->name ?? 'N/A' }}</dd>
                    </div>
                    <!-- Email -->
                    <div class="col-md-6">
                        <dt class="fw-bold">Email</dt>
                        <dd>{{ $employee->email ?? 'N/A' }}</dd>
                    </div>
                    <!-- Department -->
                    <div class="col-md-6">
                        <dt class="fw-bold">Department</dt>
                        <dd>{{ $employee->department->name ?? 'N/A' }}</dd>
                    </div>
                    <!-- Position -->
                    <div class="col-md-6">
                        <dt class="fw-bold">Position</dt>
                        <dd>{{ $employee->position ?? 'N/A' }}</dd>
                    </div>
                    <!-- Phone -->
                    <div class="col-md-6">
                        <dt class="fw-bold">Phone</dt>
                        <dd>{{ $employee->phone ?? 'N/A' }}</dd>
                    </div>
                    <!-- Address -->
                    <div class="col-md-6">
                        <dt class="fw-bold">Address</dt>
                        <dd>{{ $employee->address ?? 'N/A' }}</dd>
                    </div>
                    <!-- Status -->
                    <div class="col-md-6">
                        <dt class="fw-bold">Status</dt>
                        <dd>
                            <span class="badge bg-{{ $employee->status ? 'success' : 'danger' }} rounded-pill">
                                {{ $employee->status ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                    <!-- Created At -->
                    <div class="col-md-6">
                        <dt class="fw-bold">Created At</dt>
                        <dd>{{ $employee->created_at->format('F j, Y, g:i A') }}</dd>
                    </div>
                    <!-- Updated At -->
                    <div class="col-md-6">
                        <dt class="fw-bold">Updated At</dt>
                        <dd>{{ $employee->updated_at->format('F j, Y, g:i A') }}</dd>
                    </div>
                </dl>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-start mt-4">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Employees
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
