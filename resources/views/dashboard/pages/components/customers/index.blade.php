@extends('dashboard.layouts.app')
@section('title','customer-section')
@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
        <div class="container">
            <h1>Customers</h1>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-3">
                <a href="{{ route('customers.create') }}" class="btn btn-success">Create New Customer</a>
            </div>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->customer_id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->customer_type }}</td>
                        <td>{{ $customer->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
@endsection
