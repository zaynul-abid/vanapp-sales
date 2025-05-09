<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($item) ? 'Edit Item' : 'Create Item' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css">
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
</head>
<body>
@if(auth()->user()->isSuperAdmin())
    @include('dashboard.partials.sidebar.superadmin-sidebar')
@elseif(auth()->user()->isAdmin())
    @include('dashboard.partials.sidebar.admin-sidebar')
@endif

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ isset($item) ? 'Edit' : 'Create' }} Item</h5>
                    <a href="{{ route('employee.dashboard') }}" class="btn btn-sm btn-outline-dark">
                        <i class="ri-arrow-left-line me-1"></i> Back
                    </a>
                </div>
                <div class="card-body p-4">
                    <form id="itemForm" action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @if(isset($item))
                            @method('PUT')
                        @endif

                        <div class="row g-3">
                            <!-- Name Field -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-medium">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $item->name ?? '') }}"
                                       required data-input-index="0">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category Field -->
                            <div class="col-md-6">
                                <label for="default_category_id" class="form-label fw-medium">Category <span class="text-danger">*</span></label>
                                <select name="default_category_id" id="default_category_id" class="form-select form-select-sm @error('default_category_id') is-invalid @enderror" required data-input-index="1">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('default_category_id', $item->default_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('default_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Unit Field -->
                            <div class="col-md-6">
                                <label for="default_unit_id" class="form-label fw-medium">Unit <span class="text-danger">*</span></label>
                                <select name="default_unit_id" id="default_unit_id" class="form-select form-select-sm @error('default_unit_id') is-invalid @enderror" required data-input-index="2">
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('default_unit_id', $item->default_unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('default_unit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tax Field -->
                            <div class="col-md-6">
                                <label for="tax_id" class="form-label fw-medium">Tax <span class="text-danger">*</span></label>
                                <select name="tax_id" id="tax_id" class="form-select form-select-sm @error('tax_id') is-invalid @enderror" required data-input-index="3">
                                    <option value="">Select Tax</option>
                                    @foreach($taxes as $tax)
                                        <option value="{{ $tax->id }}" {{ old('tax_id', $item->tax_id ?? '') == $tax->id ? 'selected' : '' }}>
                                            {{ $tax->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tax_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Prices -->
                            <div class="col-md-4">
                                <label for="purchase_price" class="form-label fw-medium">Purchase Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control form-control-sm @error('purchase_price') is-invalid @enderror"
                                       id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $item->purchase_price ?? '') }}"
                                       required data-input-index="4">
                                @error('purchase_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="wholesale_price" class="form-label fw-medium">Wholesale Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control form-control-sm @error('wholesale_price') is-invalid @enderror"
                                       id="wholesale_price" name="wholesale_price" value="{{ old('wholesale_price', $item->wholesale_price ?? '') }}"
                                       required data-input-index="5">
                                @error('wholesale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="retail_price" class="form-label fw-medium">Retail Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control form-control-sm @error('retail_price') is-invalid @enderror"
                                       id="retail_price" name="retail_price" value="{{ old('retail_price', $item->retail_price ?? '') }}"
                                       required data-input-index="6">
                                @error('retail_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock Field - Conditional required attribute -->
                            <div class="col-md-6">
                                <label for="stock" class="form-label fw-medium">
                                    {{ isset($item) ? 'Stock Adjustment' : 'Opening Stock' }}
                                </label>
                                <input type="number" class="form-control form-control-sm @error('stock') is-invalid @enderror"
                                       id="stock" name="opening_stock" value="{{ old('opening_stock') }}"
                                       {{ !isset($item) ? 'required' : '' }} data-input-index="7"
                                       placeholder="{{ isset($item) ? 'Enter stock adjustment (e.g., +10 or -5)' : 'Enter initial stock' }}">
                                @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if(isset($item))
                                    <small class="text-muted">Leave blank to keep current stock: {{ $item->current_stock }}</small>
                                @endif
                            </div>

                            <!-- Image Field -->
                            <div class="col-md-6">
                                <label for="image" class="form-label fw-medium">Image</label>
                                <input type="file" class="form-control form-control-sm @error('image') is-invalid @enderror"
                                       id="image" name="image" data-input-index="8">
                                @if(isset($item) && $item->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/'.$item->image) }}" width="80" class="img-thumbnail">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                                            <label class="form-check-label small" for="remove_image">
                                                Remove current image
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status Field -->
                            <div class="col-md-6">
                                <label class="form-label fw-medium d-block">Status</label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" class="form-check-input @error('status') is-invalid @enderror"
                                           id="status" name="status" value="1"
                                        {{ old('status', $item->status ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="status">Active</label>
                                </div>
                                @error('status')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 gap-3">
                            <button type="reset" class="btn btn-outline-secondary px-4">
                                <i class="ri-refresh-line me-2"></i> Reset
                            </button>
                            <button type="submit" id="submitButton" class="btn btn-primary px-4">
                                <i class="ri-check-line me-2"></i>
                                {{ isset($item) ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('itemForm');
        const fields = Array.from(form.querySelectorAll(
            'input:not([type="submit"]):not([type="reset"]):not([type="hidden"]):not([type="file"]), textarea, select'
        )).filter(el => !el.disabled && !el.readOnly);

        // Enhanced Enter key navigation
        fields.forEach((field, index) => {
            field.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const nextField = fields[index + 1];
                    if (nextField) {
                        nextField.focus();
                    } else {
                        document.getElementById('submitButton').focus();
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
</body>
</html>
