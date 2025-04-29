@extends('dashboard.layouts.app')
@section('title', isset($item) ? 'Edit item' : 'Create item')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container py-3 px-2 px-md-3" style="max-width: 600px;">
        <div class="card shadow-sm p-3 p-md-4">
            <h1 class="card-title mb-3 fs-4 fw-bold">{{ isset($item) ? 'Edit' : 'Create' }} Item</h1>

            <form id="itemForm" action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($item))
                    @method('PUT')
                @endif

                <div class="mb-2">
                    <label for="name" class="form-label small fw-medium">Name</label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm" value="{{ old('name', $item->name ?? '') }}" required>
                    @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="default_category_id" class="form-label small fw-medium">Category</label>
                    <select name="default_category_id" id="default_category_id" class="form-select form-select-sm" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('default_category_id', $item->default_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('default_category_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="default_unit_id" class="form-label small fw-medium">Unit</label>
                    <select name="default_unit_id" id="default_unit_id" class="form-select form-select-sm" required>
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('default_unit_id', $item->default_unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('default_unit_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="tax_id" class="form-label small fw-medium">Tax</label>
                    <select name="tax_id" id="tax_id" class="form-select form-select-sm">
                        <option value="">Select Tax</option>
                        @foreach($taxes as $tax)
                            <option value="{{ $tax->id }}" {{ old('tax_id', $item->tax_id ?? '') == $tax->id ? 'selected' : '' }}>
                                {{ $tax->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tax_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="purchase_price" class="form-label small fw-medium">Purchase Price</label>
                    <input type="number" step="0.01" name="purchase_price" id="purchase_price" class="form-control form-control-sm" value="{{ old('purchase_price', $item->purchase_price ?? '') }}" required>
                    @error('purchase_price')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="wholesale_price" class="form-label small fw-medium">Wholesale Price</label>
                    <input type="number" step="0.01" name="wholesale_price" id="wholesale_price" class="form-control form-control-sm" value="{{ old('wholesale_price', $item->wholesale_price ?? '') }}" required>
                    @error('wholesale_price')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="retail_price" class="form-label small fw-medium">Retail Price</label>
                    <input type="number" step="0.01" name="retail_price" id="retail_price" class="form-control form-control-sm" value="{{ old('retail_price', $item->retail_price ?? '') }}" required>
                    @error('retail_price')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="opening_stock" class="form-label small fw-medium">Opening Stock</label>
                    <input type="number" name="opening_stock" id="opening_stock" class="form-control form-control-sm" value="{{ old('opening_stock', $item->opening_stock ?? '') }}" required>
                    @error('opening_stock')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                @if(isset($item) && $item->current_stock !== null)
                    <div class="mb-2">
                        <label for="current_stock" class="form-label small fw-medium">Current Stock</label>
                        <input type="number" name="current_stock" id="current_stock" class="form-control form-control-sm bg-light" value="{{ old('current_stock', $item->current_stock) }}" readonly disabled>
                    </div>
                @endif

                <div class="mb-2">
                    <label for="image" class="form-label small fw-medium">Image</label>
                    <input type="file" name="image" id="image" class="form-control form-control-sm">
                    @if(isset($item) && $item->image)
                        <img src="{{ asset('storage/'.$item->image) }}" alt="Item Image" class="mt-2 rounded" style="width: 80px; height: 80px; object-fit: cover;">
                    @endif
                    @error('image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label for="status" class="form-label small fw-medium">Status</label>
                    <select name="status" id="status" class="form-select form-select-sm" required>
                        <option value="1" {{ old('status', $item->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $item->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" id="submitButton" class="btn btn-primary btn-sm px-3 py-1 mt-3">{{ isset($item) ? 'Update' : 'Create' }}</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('itemForm');
            const submitButton = document.getElementById('submitButton');
            const currentStockInput = document.getElementById('current_stock');

            if (currentStockInput) {
                currentStockInput.disabled = true;
                currentStockInput.setAttribute('readonly', 'readonly');
            }

            form.addEventListener('submit', function () {
                submitButton.disabled = true;
                submitButton.textContent = 'Processing...';
                submitButton.classList.add('opacity-50');
            });
        });
    </script>
@endsection
