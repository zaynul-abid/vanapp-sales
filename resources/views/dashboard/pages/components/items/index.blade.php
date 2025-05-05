@extends('dashboard.layouts.app')
@section('title', 'Items')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Items</h4>
            <a href="{{ route('items.create') }}" class="btn btn-dark">
                <i class="ti ti-plus me-1"></i> Add Item
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Category</th>
                        <th class="d-none d-lg-table-cell">Unit</th>
                        <th class="d-none d-lg-table-cell">Tax</th>
                        <th>Prices</th>
                        <th>Stock</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($items as $key => $item)
                        <tr>
                            <td>{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="d-none d-md-table-cell">{{ $item->category->name ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell">{{ $item->unit->name ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell">{{ $item->tax->name ?? '-' }}</td>
                            <td>
                                <div><small class="text-muted">P:</small> {{ $item->purchase_price }}</div>
                                <div><small class="text-muted">W:</small> {{ $item->wholesale_price }}</div>
                                <div><small class="text-muted">R:</small> {{ $item->retail_price }}</div>
                            </td>
                            <td>{{ $item->current_stock }}</td>
                            <td>
                                @if($item->image)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal{{ $item->id }}">
                                        <img src="{{ asset('storage/'.$item->image) }}" width="40" class="img-thumbnail rounded">
                                    </a>
                                    <!-- Image Modal -->
                                    <div class="modal fade" id="imageModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $item->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ asset('storage/'.$item->image) }}" class="img-fluid" style="max-height: 70vh;">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-secondary">No Image</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->status ? 'success' : 'danger' }}">
                                    {{ $item->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('show-unit', $item->id) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Change Unit">
                                        <i class="ti ti-exchange me-1 d-none d-sm-inline"></i>Create Unit
                                        <span class="d-inline d-sm-none">Unit</span>
                                    </a>

                                    <a href="{{ route('items.edit', $item->id) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       data-bs-toggle="tooltip"
                                       title="Edit">
                                        <i class="ti ti-edit me-1 d-none d-sm-inline"></i>Edit
                                        <span class="d-inline d-sm-none">Edit</span>
                                    </a>

                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip"
                                                title="Delete"
                                                onclick="return confirm('Are you sure? This will permanently delete the item.')">
                                            <i class="ti ti-trash me-1 d-none d-sm-inline"></i>Delete
                                            <span class="d-inline d-sm-none">Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No items found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .img-thumbnail {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .img-thumbnail:hover {
            transform: scale(1.1);
        }
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .table td, .table th {
                white-space: nowrap;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Initialize tooltips and modals
        document.addEventListener('DOMContentLoaded', function() {
            // Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Image modal click handler
            document.querySelectorAll('.img-thumbnail').forEach(img => {
                img.addEventListener('click', function(e) {
                    e.preventDefault();
                    const modalId = this.closest('a').getAttribute('data-bs-target');
                    const modal = new bootstrap.Modal(document.querySelector(modalId));
                    modal.show();
                });
            });
        });
    </script>
@endpush
