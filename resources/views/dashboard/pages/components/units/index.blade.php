@extends('dashboard.layouts.app')
@section('title','unit-index')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container">
        <a href="{{ route('units.create') }}" class="btn btn-success mb-3">+ Add Unit</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <tr>
                <th>ID</th><th>Name</th><th>Status</th><th>Actions</th>
            </tr>
            @foreach($units as $unit)
                <tr>
                    <td>{{ $unit->id }}</td>
                    <td>{{ $unit->name }}</td>
                    <td>{{ $unit->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('units.edit', $unit) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('units.destroy', $unit) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this unit?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
