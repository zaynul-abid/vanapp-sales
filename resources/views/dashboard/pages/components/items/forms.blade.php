@extends('dashboard.layouts.app')
@section('title', isset($van) ? 'Edit item' : 'Create item')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection
@section('content')
    <div class="container">
        <h1>{{ isset($item) ? 'Edit' : 'Create' }} Item</h1>

        <form action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($item))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $item->name ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label>Category</label>
                <select name="default_category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('default_category_id', $item->default_category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Unit</label>
                <select name="default_unit_id" class="form-control" required>
                    <option value="">Select Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('default_unit_id', $item->default_unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Tax</label>
                <select name="tax_id" class="form-control">
                    <option value="">Select Tax</option>
                    @foreach($taxes as $tax)
                        <option value="{{ $tax->id }}" {{ old('tax_id', $item->tax_id ?? '') == $tax->id ? 'selected' : '' }}>
                            {{ $tax->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Purchase Price</label>
                <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price', $item->purchase_price ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label>Wholesale Price</label>
                <input type="number" step="0.01" name="wholesale_price" class="form-control" value="{{ old('wholesale_price', $item->wholesale_price ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label>Retail Price</label>
                <input type="number" step="0.01" name="retail_price" class="form-control" value="{{ old('retail_price', $item->retail_price ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label>Opening Stock</label>
                <input type="number" name="opening_stock" class="form-control" value="{{ old('opening_stock', $item->opening_stock ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label>Current Stock</label>
                <input type="number" name="current_stock" class="form-control" value="{{ old('current_stock', $item->current_stock ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label>Image</label>
                <input type="file" name="image" class="form-control">
                @if(isset($item) && $item->image)
                    <img src="{{ asset('storage/'.$item->image) }}" width="100" class="mt-2">
                @endif
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="1" {{ old('status', $item->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $item->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">{{ isset($item) ? 'Update' : 'Create' }}</button>
        </form>
    </div>
@endsection
