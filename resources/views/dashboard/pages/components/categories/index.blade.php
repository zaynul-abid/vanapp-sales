@extends('dashboard.layouts.app')
@section('title','Category-section')
@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container">
        <a href="{{ route('categories.create') }}" class="btn btn-success mb-3">+ Add Category</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <tr>
                <th>ID</th><th>Name</th><th>Status</th><th>Actions</th>
            </tr>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
