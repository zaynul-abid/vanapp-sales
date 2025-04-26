@extends('dashboard.layouts.app')
@section('title', isset($van) ? 'Edit Unit' : 'Create Unit')


@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection


@section('content')
    <div class="container">
        <h2>{{ isset($unit) ? 'Edit' : 'Create' }} Unit</h2>
        <form method="POST" action="{{ isset($unit) ? route('units.update', $unit) : route('units.store') }}">
            @csrf
            @if(isset($unit))
                @method('PUT')
            @endif

            <div class="form-group">
                <label>Unit Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $unit->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description', $unit->description ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ old('status', $unit->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $unit->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
