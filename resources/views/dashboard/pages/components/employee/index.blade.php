@extends('dashboard.layouts.app')
@section('title', 'Employees')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Employees</h2>
        <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">Add Employee</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th><th>Email</th><th>Phone</th><th>Position</th><th>Status</th><th>Department</th><th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($employees as $emp)
                <tr>
                    <td>{{ $emp->name }}</td>
                    <td>{{ $emp->email }}</td>
                    <td>{{ $emp->phone }}</td>
                    <td>{{ $emp->position }}</td>
                    <td>{{ ucfirst($emp->status) }}</td>
                    <td>{{ $emp->department->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $employees->links() }}
    </div>
@endsection
