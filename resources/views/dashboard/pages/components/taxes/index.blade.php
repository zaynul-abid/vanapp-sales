
@extends('dashboard.layouts.app')
@section('title','tax-page')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection


@section('content')
    <div class="container">
        <a href="{{ route('taxes.create') }}" class="btn btn-primary mb-3">+ Add Tax</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr><th>Name</th><th>Percentage</th><th>Description</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @foreach($taxes as $tax)
                <tr>
                    <td>{{ $tax->name }}</td>
                    <td>{{ $tax->tax_percentage }}%</td>
                    <td>{{ $tax->description }}</td>
                    <td>
                        <a href="{{ route('taxes.edit', $tax) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('taxes.destroy', $tax) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Delete?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
