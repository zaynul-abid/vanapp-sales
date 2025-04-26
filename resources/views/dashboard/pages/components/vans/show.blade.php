@extends('dashboard.layouts.app')
@section('title','van-show')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Van Details
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $van->name }}</h5>
                <p class="card-text">
                    <strong>Register Number:</strong> {{ $van->register_number }}<br>
                    <strong>Status:</strong>
                    <span class="badge {{ $van->status ? 'bg-success' : 'bg-secondary' }}">
                    {{ $van->status ? 'Active' : 'Inactive' }}
                </span>
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ route('vans.edit', $van->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('vans.destroy', $van->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    <a href="{{ route('vans.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>

@endsection
