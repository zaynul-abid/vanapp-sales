@extends('dashboard.layouts.app')
@section('title', 'Unit Conversion')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container py-3 px-2 px-md-3" style="max-width: 600px;">
        <!-- Conversion Form Card -->
        <div class="card shadow-sm p-3 p-md-4 mb-4">
            <h2 class="card-title mb-3 fs-4 fw-bold d-flex align-items-center">
                <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m-16 6h12m0 0l-4-4m4 4l-4 4"></path>
                </svg>
                Unit Conversion
            </h2>

            <form action="{{ route('item.create-unit', $unitItem->id) }}" method="POST" id="conversionForm">
                @csrf
                @method('PUT')

                <!-- Current Unit Info -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded">
                            <h6 class="text-primary fw-semibold">Item</h6>
                            <p class="mb-0">{{ $unitItem->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded">
                            <h6 class="text-primary fw-semibold">Current Unit</h6>
                            <p class="mb-0">{{ $unitItem->unit->name ?? 'N/A' }}</p>
                            <input type="hidden" name="base_unit_id" value="{{ $unitItem->unit?->id }}">
                        </div>
                    </div>
                </div>

                <!-- Conversion Form -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="unit_name" class="form-label small fw-medium">Convert To</label>
                        <select name="unit_name" id="unit_name" class="form-select form-select-sm" required>
                            <option value="">-- Select Unit --</option>
                            @foreach ($alternateUnits as $alternateUnit)
                                @if($alternateUnit->id != $unitItem->unit?->id)
                                    <option value="{{ $alternateUnit->name }}">{{ $alternateUnit->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('unit_name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="quantity" class="form-label small fw-medium">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control form-control-sm" min="1" step="1" required>
                        @error('quantity')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="wholesale_rate" class="form-label small fw-medium">Wholesale Rate</label>
                        <input type="number" name="wholesale_price" id="wholesale_rate" class="form-control form-control-sm bg-light" readonly required>
                        @error('wholesale_price')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="retail_rate" class="form-label small fw-medium">Retail Rate</label>
                        <input type="number" name="retail_price" id="retail_rate" class="form-control form-control-sm bg-light" readonly required>
                        @error('retail_price')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <input type="hidden" name="name" value="{{ $unitItem->name }}">
                <input type="hidden" name="tax_percentage" value="{{ $unitItem->tax->tax_percentage ?? 0 }}">
                <input type="hidden" name="current_stock" value="{{ $unitItem->current_stock }}">

                <!-- Buttons -->
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('items.index') }}" class="btn btn-secondary btn-sm px-3 py-1 d-flex align-items-center">
                        <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" id="submitButton" class="btn btn-primary btn-sm px-3 py-1 d-flex align-items-center">
                        <svg class="me-1" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Conversion
                    </button>
                </div>
            </form>
        </div>

        <!-- Converted Units Table -->
        <div class="card shadow-sm p-3 p-md-4">
            <h3 class="card-title mb-3 fs-5 fw-bold">Converted Units</h3>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                    <tr>
                        <th scope="col" class="p-2">#</th>
                        <th scope="col" class="p-2">Name</th>
                        <th scope="col" class="p-2">Unit</th>
                        <th scope="col" class="p-2">Quantity</th>
                        <th scope="col" class="p-2">Wholesale Rate</th>
                        <th scope="col" class="p-2">Retail Rate</th>
                        <th scope="col" class="p-2">Type</th>
                        <th scope="col" class="p-2">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($changedUnits as $index => $changedunit)
                        <tr>
                            <td class="p-2">{{ $changedUnits->firstItem() + $index }}</td>
                            <td class="p-2">{{ $changedunit->name }}</td>
                            <td class="p-2">{{ $changedunit->unit_name }}</td>
                            <td class="p-2">{{ $changedunit->quantity }}</td>
                            <td class="p-2">{{ $changedunit->wholesale_price }}</td>
                            <td class="p-2">{{ $changedunit->retail_price }}</td>
                            <td class="p-2">{{ $changedunit->type }}</td>
                            <td class="p-2">
                                <a href="{{ route('unit.item.delete', $changedunit->id) }}"
                                   class="btn btn-danger btn-sm px-2 py-1 delete-btn"
                                   data-id="{{ $changedunit->id }}">
                                    <svg class="me-1" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Showing {{ $changedUnits->firstItem() }} to {{ $changedUnits->lastItem() }} of {{ $changedUnits->total() }} entries
                </div>
                <div>
                    {{ $changedUnits->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Rate Calculation and Form Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Rate Calculation
            const quantityInput = document.getElementById('quantity');
            const wholesaleRateInput = document.getElementById('wholesale_rate');
            const retailRateInput = document.getElementById('retail_rate');
            const itemWholesalePrice = {{ $unitItem->wholesale_price ?? 0 }};
            const itemRetailPrice = {{ $unitItem->retail_price ?? 0 }};

            function updateRates() {
                const quantity = parseFloat(quantityInput.value) || 0;
                wholesaleRateInput.value = (itemWholesalePrice * quantity).toFixed(2);
                retailRateInput.value = (itemRetailPrice * quantity).toFixed(2);
            }

            quantityInput.addEventListener('input', updateRates);
            document.getElementById('unit_name').addEventListener('change', function () {
                // Reset quantity and rates when unit changes
                quantityInput.value = '';
                wholesaleRateInput.value = '';
                retailRateInput.value = '';
            });

            // Delete Confirmation
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to delete this unit conversion?')) {
                        window.location.href = this.href;
                    }
                });
            });

            // Form Submission Animation
            const form = document.getElementById('conversionForm');
            const submitButton = document.getElementById('submitButton');
            form.addEventListener('submit', function () {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Processing...
                `;
            });
        });
    </script>
@endsection
