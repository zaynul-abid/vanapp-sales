@extends('dashboard.layouts.app')
@section('title', 'Van Show')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <style>
        @media (max-width: 576px) {
            .card-title {
                font-size: 1.25rem;
            }
            .card-text {
                font-size: 0.9rem;
            }
            .btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
            .d-flex.gap-2 {
                flex-wrap: wrap;
                gap: 0.5rem !important;
            }
            .d-flex.gap-2 .btn {
                flex: 1 1 100%;
                text-align: center;
            }
        }
        @media (min-width: 577px) and (max-width: 768px) {
            .card-title {
                font-size: 1.5rem;
            }
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.95rem;
            }
        }
    </style>

    <div class="container mt-4" style="max-width: 600px;">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="mb-0">Van Details</h3>
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
