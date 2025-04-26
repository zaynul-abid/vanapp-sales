@extends('dashboard.layouts.app')
@section('title', isset($van) ? 'Edit Tax' : 'Create Tax')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container">
        <h2>{{ isset($tax) ? 'Edit Tax' : 'Add Tax' }}</h2>

        <form method="POST" action="{{ isset($tax) ? route('taxes.update', $tax) : route('taxes.store') }}">
            @csrf
            @if(isset($tax)) @method('PUT') @endif

            <div class="form-group mb-2">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $tax->name ?? '') }}" required>
            </div>

            <div class="form-group mb-2">
                <label>Tax Percentage</label>
                <input type="number" step="0.01" name="tax_percentage" class="form-control" value="{{ old('tax_percentage', $tax->tax_percentage ?? '') }}" required>
            </div>

            <div class="form-group mb-2">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description', $tax->description ?? '') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">{{ isset($tax) ? 'Update' : 'Create' }}</button>
        </form>
    </div>
@endsection
