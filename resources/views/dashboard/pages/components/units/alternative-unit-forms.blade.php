@extends('dashboard.layouts.app')
@section('title', isset($alternative_unit) ? 'Edit Unit' : 'Create Unit')


@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection


@section('content')

    <div class="container">
        <h2>{{ isset($alternative_unit) ? 'Edit' : 'Create' }} Unit</h2>
        <form method="POST" action="{{ isset($alternative_unit) ? route('alternative-units.update', $alternative_unit) : route('alternative-units.store') }}">
            @csrf
            @if(isset($alternative_unit))
                @method('PUT')
            @endif

            <div class="form-group">
                <label>Alternative Unit Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $alternative_unit->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description', $alternative_unit->description ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ old('status', $alternative_unit->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $alternative_unit->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
@endsection
