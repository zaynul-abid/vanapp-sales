@extends('dashboard.layouts.app')
@section('title', 'Sales Report')

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
            <h4>Sales Report</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('sales.create') }}" class="btn btn-dark">
                    <i class="ti ti-plus me-1"></i> New Sale
                </a>
            </div>
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
            <table id="datatablesSimple" class="table">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Bill No</th>
                    <th>Sale Date</th>
                    <th>Customer</th>
                    <th>Sale Type</th>
                    <th>Net Gross</th>
                    <th>Tax</th>
                    <th>Discount</th>
                    <th>Employee</th>
                    <th>Van</th>
                    <th>Net Total</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($saleMasters as $key => $saleMaster)
                    <tr>
                        <td>{{ $saleMasters->firstItem() + $key }}</td>
                        <td>{{ $saleMaster->bill_no }}</td>
                        <td>{{ $saleMaster->sale_date }}</td>
                        <td>{{ $saleMaster->customer->name ?? 'N/A' }}</td>
                        <td>{{ $saleMaster->sale_type }}</td>
                        <td>{{ number_format($saleMaster->net_gross_amount, 2) }}</td>
                        <td>{{ number_format($saleMaster->net_tax_amount, 2) }}</td>
                        <td>{{ number_format($saleMaster->discount, 2) }}</td>
                        <td>{{ $saleMaster->user->name ?? 'N/A' }}</td>
                        <td>{{ $saleMaster->van->name ?? 'N/A' }}</td>
                        <td>{{ number_format($saleMaster->net_total_amount, 2) }}</td>
                        <td>
                            <a href="{{ route('saleItem.details', $saleMaster->id) }}" class="btn btn-sm btn-info">
                                <i class="ti ti-eye"></i> View
                            </a>

{{--                            <button class="btn btn-sm btn-danger delete-btn"--}}
{{--                                    data-bs-toggle="modal"--}}
{{--                                    data-bs-target="#deleteModal"--}}
{{--                                    data-id="{{ $saleMaster->id }}">--}}
{{--                                <i class="ti ti-trash"></i> Delete--}}
{{--                            </button>--}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted">No sales found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $saleMasters->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this sale record? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const saleId = this.getAttribute('data-id');
                    // This matches the resourceful route pattern
                    deleteForm.action = `/sales/${saleId}`;
                });
            });
        });
    </script>
@endpush
