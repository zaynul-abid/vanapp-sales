@extends('dashboard.layouts.app')
@section('title','alternative-unit-index')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container">
        <a href="{{ route('alternative-units.create') }}" class="btn btn-success mb-3">+ Add Unit</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <tr>
                <th>ID</th><th>Name</th><th>Status</th><th>Actions</th>
            </tr>
            @foreach($alternativeUnits as $alterunit)
                <tr>
                    <td>{{ $alterunit->id }}</td>
                    <td>{{ $alterunit->name }}</td>
                    <td>{{ $alterunit->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('alternative-units.edit', $alterunit) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('alternative-units.destroy', $alterunit) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this unit?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
