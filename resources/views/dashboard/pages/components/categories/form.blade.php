
@extends('dashboard.layouts.app')
@section('title', isset($category) ? 'Edit Category' : 'Create Category')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection
@section('content')
    <div class="container">
        <h2>{{ isset($category) ? 'Edit' : 'Create' }} Category</h2>
        <form method="POST" action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}">
            @csrf
            @if(isset($category))
                @method('PUT')
            @endif

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ old('status', $category->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $category->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
