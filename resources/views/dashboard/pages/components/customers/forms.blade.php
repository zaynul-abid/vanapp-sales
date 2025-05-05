@extends('dashboard.layouts.app')
@section('title', isset($customer) ? 'Edit Customer' : 'Create Customer')

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
                        <h5 class="mb-0">{{ isset($customer) ? 'Edit' : 'Create' }} Customer</h5>
                        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-dark">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <form id="customerForm" method="POST" action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}" class="needs-validation" novalidate>
                            @csrf
                            @if(isset($customer))
                                @method('PUT')
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-medium">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $customer->name ?? '') }}"
                                           required data-input-index="0">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $customer->email ?? '') }}"
                                           required data-input-index="1">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-medium">Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $customer->phone ?? '') }}"
                                           required data-input-index="2">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="customer_type" class="form-label fw-medium">Customer Type <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('customer_type') is-invalid @enderror"
                                            id="customer_type" name="customer_type" required data-input-index="3">
                                        <option value="">Select Type</option>
                                        <option value="Individual" @selected(old('customer_type', $customer->customer_type ?? '') == 'Individual')>Individual</option>
                                        <option value="Business" @selected(old('customer_type', $customer->customer_type ?? '') == 'Business')>Business</option>
                                    </select>
                                    @error('customer_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label fw-medium">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('address') is-invalid @enderror"
                                           id="address" name="address" value="{{ old('address', $customer->address ?? '') }}"
                                           required data-input-index="4">
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror"
                                               id="is_active" name="is_active" value="1"
                                            {{ old('is_active', $customer->is_active ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="is_active">Active</label>
                                    </div>
                                    @error('is_active')
                                    <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 gap-3">
                                <button type="reset" class="btn btn-outline-secondary px-4">
                                    <i class="ti ti-reload me-2"></i> Reset
                                </button>
                                <button type="submit" id="submitBtn" class="btn btn-primary px-4">
                                    <i class="ti ti-check me-2"></i>
                                    {{ isset($customer) ? 'Update' : 'Create' }}
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
            const form = document.getElementById('customerForm');
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
