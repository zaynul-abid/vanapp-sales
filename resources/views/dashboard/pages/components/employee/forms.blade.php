@extends('dashboard.layouts.app')
@section('title', isset($employee) ? 'Edit Employee' : 'Create Employee')
@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection
@section('content')
    <div class="container">
        <h2>{{ isset($employee) ? 'Edit' : 'Create' }} Employee</h2>

        <form action="{{ isset($employee) ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST">
            @csrf
            @if(isset($employee))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $employee->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $employee->email ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $employee->phone ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $employee->address ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $employee->position ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="department_id">Department</label>
                <select class="form-control" id="department_id" name="department_id" required>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ (old('department_id', $employee->department_id ?? '') == $department->id) ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active" {{ (old('status', $employee->status ?? '') == 'active') ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ (old('status', $employee->status ?? '') == 'inactive') ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" {{ isset($employee) ? '' : 'required' }}>
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($employee) ? 'Update' : 'Create' }} Employee</button>
        </form>
    </div>
@endsection
