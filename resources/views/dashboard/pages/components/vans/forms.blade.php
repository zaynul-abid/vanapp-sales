@extends('dashboard.layouts.app')
@section('title', isset($van) ? 'Edit Van' : 'Create Van')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container mt-4" style="max-width: 600px;">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h1 class="card-title mb-0">{{ isset($van) ? 'Edit' : 'Create' }} Van</h1>
            </div>
            <div class="card-body">
                <form action="{{ isset($van) ? route('vans.update', $van->id) : route('vans.store') }}" method="POST" id="vanForm">
                    @csrf
                    @if(isset($van))
                        @method('PUT')
                    @endif

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Van Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $van->name ?? '') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Registration Number Field -->
                    <div class="mb-3">
                        <label for="register_number" class="form-label">Registration Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('register_number') is-invalid @enderror"
                               id="register_number" name="register_number"
                               value="{{ old('register_number', $van->register_number ?? '') }}"
                               placeholder="e.g. ABC123, XYZ-456" required>
                        @error('register_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Alphanumeric characters and hyphens only (max 20 characters)
                        </small>
                    </div>

                    <!-- Status Field -->
                    <div class="mb-3 form-check form-switch">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1"
                            {{ old('status', isset($van) ? $van->status : true) ? 'checked' : '' }} />
                        <label class="form-check-label" for="status">Active</label>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('vans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitButton">
                            <i class="fas fa-save me-1"></i>
                            {{ isset($van) ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('vanForm');
                const inputs = Array.from(form.querySelectorAll('input:not([type="hidden"])')); // Exclude hidden inputs
                const submitButton = document.getElementById('submitButton');

                // Prevent form submission on Enter key for all inputs
                form.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault(); // Prevent default form submission
                        const activeElement = document.activeElement;
                        const currentIndex = inputs.indexOf(activeElement);
                        if (currentIndex >= 0 && currentIndex < inputs.length - 1) {
                            inputs[currentIndex + 1].focus(); // Move to next input
                        }
                    }
                });

                // Handle Escape key for navigation
                form.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        event.preventDefault();
                        const activeElement = document.activeElement;
                        const currentIndex = inputs.indexOf(activeElement);
                        if (currentIndex > 0) {
                            inputs[currentIndex - 1].focus(); // Move to previous input
                        }
                    }
                });

                // Ensure form only submits when submit button is explicitly clicked
                form.addEventListener('submit', function(event) {
                    if (!event.submitter || event.submitter.id !== 'submitButton') {
                        event.preventDefault(); // Block submission unless submit button is clicked
                    }
                });
            });
        </script>
    @endsection

@endsection
