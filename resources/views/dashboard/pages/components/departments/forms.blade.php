@extends('dashboard.layouts.app')
@section('title', isset($department) ? 'Edit Department' : 'Create Department')

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
                <h1 class="card-title mb-0">{{ isset($department) ? 'Edit' : 'Create' }} Department</h1>
            </div>
            <div class="card-body">
                <form action="{{ isset($department) ? route('departments.update', $department->id) : route('departments.store') }}" method="POST">
                    @csrf
                    @if(isset($department))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $department->name ?? '') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" class="form-check-input" id="status" name="status" value="1"
                                    {{ (old('status', $department->status ?? true)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                            @error('status')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description', $department->description ?? '') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            {{ isset($department) ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
