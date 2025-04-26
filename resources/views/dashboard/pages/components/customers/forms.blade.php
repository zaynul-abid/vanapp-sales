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
    <div class="container">
        <h1>{{ isset($customer) ? 'Edit' : 'Create' }} Customer</h1>

        <form action="{{ isset($customer) ? route('customers.update', $customer->id) : route('customers.store') }}" method="POST">
            @csrf
            @if(isset($customer))
                @method('PUT')
            @endif


            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="{{ old('name', $customer->name ?? '') }}" required>
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="{{ old('email', $customer->email ?? '') }}" required>
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="number" class="form-control" id="phone" name="phone"
                       value="{{ old('phone', $customer->phone ?? '') }}" required>
                @error('phone')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address"
                       value="{{ old('address', $customer->address ?? '') }}" required>
                @error('address')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="customer_type" class="form-label">Customer Type</label>
                <select class="form-control" id="customer_type" name="customer_type" required>
                    <option value="">Select Type</option>
                    <option value="Individual" @selected(old('customer_type', $customer->customer_type ?? '') == 'Individual')>Individual</option>
                    <option value="Business" @selected(old('customer_type', $customer->customer_type ?? '') == 'Business')>Business</option> : '' }}>Business</option>
                </select>
                @error('customer_type')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                       value="1" {{ old('is_active', $customer->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($customer) ? 'Update' : 'Create' }}</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
