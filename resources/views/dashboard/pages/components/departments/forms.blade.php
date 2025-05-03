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
    <div class="card mx-auto" style="max-width: 750px;"> <!-- Increased width to 750px -->
        <div class="card-header d-flex justify-content-between align-items-center py-3 bg-light">
            <h4 class="mb-0 fw-semibold">{{ isset($department) ? 'Edit Department' : 'Create New Department' }}</h4>
            <a href="{{ route('departments.index') }}" class="btn btn-sm btn-outline-dark">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="card-body p-4">
            <form action="{{ isset($department) ? route('departments.update', $department->id) : route('departments.store') }}"
                  method="POST"
                  id="departmentForm"
                  class="needs-validation" novalidate>

                @csrf
                @if(isset($department))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <!-- Name Field -->
                    <div class="col-md-8">
                        <label for="name" class="form-label fw-medium">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $department->name ?? '') }}"
                               required data-next="description">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Field -->
                    <div class="col-md-4">
                        <label class="form-label fw-medium d-block">Status</label>
                        <div class="form-check form-switch">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" class="form-check-input" id="status" name="status" value="1"
                                {{ (old('status', $department->status ?? true)) ? 'checked' : '' }}>
                            <label class="form-check-label ms-2" for="status">Active</label>
                        </div>
                        @error('status')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description Field -->
                    <div class="col-12">
                        <label for="description" class="form-label fw-medium">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3"
                                  data-next="submitBtn">{{ old('description', $department->description ?? '') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 gap-3">
                    <button type="reset" class="btn btn-outline-secondary px-4">
                        <i class="ti ti-reload me-2"></i> Reset
                    </button>
                    <button type="submit" id="submitBtn" class="btn btn-primary px-4">
                        <i class="ti ti-check me-2"></i>
                        {{ isset($department) ? 'Update Department' : 'Create Department' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced Enter key navigation
            const form = document.getElementById('departmentForm');
            const fields = Array.from(form.elements).filter(el =>
                ['INPUT', 'TEXTAREA', 'SELECT'].includes(el.tagName) &&
                !['hidden', 'submit', 'reset', 'button'].includes(el.type)
            );

            fields.forEach((field, index) => {
                field.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const nextField = fields[index + 1];
                        if (nextField) {
                            nextField.focus();
                            if (nextField.tagName === 'SELECT') {
                                nextField.click();
                            }
                        }
                    }
                });
            });

            // Bootstrap form validation
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>
@endsection
