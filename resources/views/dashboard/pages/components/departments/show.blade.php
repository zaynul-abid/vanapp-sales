@extends('dashboard.layouts.app')
@section('title', 'Department show')

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
            <div class="card-header bg-white">
                <h1 class="card-title mb-0">Department Details</h1>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h5>Name:</h5>
                        <p>{{ $department->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Status:</h5>
                        <span class="badge bg-{{ $department->status ? 'success' : 'danger' }}">
                        {{ $department->status ? 'Active' : 'Inactive' }}
                    </span>
                    </div>
                </div>
                <div class="mb-3">
                    <h5>Description:</h5>
                    <p>{{ $department->description ?? 'N/A' }}</p>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                    <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
