@extends('dashboard.layouts.app')
@section('title','items-home')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection
@section('content')
    <div class="container">
        <h1>Items</h1>
        <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">Add Item</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Unit</th>
                <th>Tax</th>
                <th>Purchase Price</th>
                <th>Wholesale Price</th>
                <th>Retail Price</th>
                <th>Opening Stock</th>
                <th>Current Stock</th>
                <th>Image</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td>{{ $item->unit->name ?? '-' }}</td>
                    <td>{{ $item->tax->name ?? '-' }}</td>
                    <td>{{ $item->purchase_price }}</td>
                    <td>{{ $item->wholesale_price }}</td>
                    <td>{{ $item->retail_price }}</td>
                    <td>{{ $item->opening_stock }}</td>
                    <td>{{ $item->current_stock }}</td>
                    <td>
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" width="50">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>{{ $item->status ? 'Active' : 'Inactive' }}</td>
                    <td class="text-nowrap">
                        <div class="d-flex gap-2">
                            <!-- Change Unit Button -->
                            <a href="{{ route('show-unit', $item->id) }}"
                               class="btn btn-sm btn-outline-primary"
                               title="Change Unit"
                               data-bs-toggle="tooltip">
                                <i class="fas fa-exchange-alt"></i>
                            </a>

                            <!-- Edit Button -->
                            <a href="{{ route('items.edit', $item->id) }}"
                               class="btn btn-sm btn-outline-warning"
                               title="Edit Item"
                               data-bs-toggle="tooltip">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete Item"
                                        data-bs-toggle="tooltip"
                                        onclick="return confirm('Are you sure you want to delete this item? This action cannot be undone.')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>

                            <!-- View Details Button (optional) -->
{{--                            <a href="{{ route('items.show', $item->id) }}"--}}
{{--                               class="btn btn-sm btn-outline-info"--}}
{{--                               title="View Details"--}}
{{--                               data-bs-toggle="tooltip">--}}
{{--                                <i class="fas fa-eye"></i>--}}
{{--                            </a>--}}
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
