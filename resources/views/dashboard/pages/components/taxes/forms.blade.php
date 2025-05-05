@extends('dashboard.layouts.app')
@section('title', isset($tax) ? 'Edit Tax' : 'Create Tax')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header p-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ isset($tax) ? 'Edit' : 'Create' }} Tax</h5>
                        <a href="{{ route('taxes.index') }}" class="btn btn-sm btn-outline-dark">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <form id="taxForm" method="POST" action="{{ isset($tax) ? route('taxes.update', $tax) : route('taxes.store') }}" class="needs-validation" novalidate>
                            @csrf
                            @if(isset($tax))
                                @method('PUT')
                            @endif

                            <!-- Rest of your form fields remain the same -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-medium">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $tax->name ?? '') }}"
                                           required data-input-index="0">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tax_percentage" class="form-label fw-medium">Percentage <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control form-control-sm @error('tax_percentage') is-invalid @enderror"
                                           id="tax_percentage" name="tax_percentage"
                                           value="{{ old('tax_percentage', $tax->tax_percentage ?? '') }}"
                                           required data-input-index="1">
                                    @error('tax_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label fw-medium">Description</label>
                                    <textarea class="form-control form-control-sm @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3"
                                              data-input-index="2">{{ old('description', $tax->description ?? '') }}</textarea>
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
                                    {{ isset($tax) ? 'Update Tax' : 'Create Tax' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('taxForm');
            const fields = Array.from(form.querySelectorAll(
                'input:not([type="submit"]):not([type="reset"]):not([type="hidden"]), textarea, select'
            )).filter(el => !el.disabled);

            // Enhanced Enter key navigation
            fields.forEach((field, index) => {
                field.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const nextField = fields[index + 1];
                        if (nextField) {
                            nextField.focus();
                        } else {
                            document.getElementById('submitBtn').focus();
                        }
                    }
                    else if (e.key === 'Escape') {
                        e.preventDefault();
                        const prevField = fields[index - 1];
                        if (prevField) {
                            prevField.focus();
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
@endpush

@push('styles')
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .form-control-sm, .form-select-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.875rem;
        }
        @media (max-width: 768px) {
            .col-md-8 {
                width: 100%;
            }
        }
    </style>
@endpush
