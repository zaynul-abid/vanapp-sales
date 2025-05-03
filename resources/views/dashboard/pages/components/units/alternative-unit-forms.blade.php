@extends('dashboard.layouts.app')
@section('title', isset($alternative_unit) ? 'Edit Alternative Unit' : 'Create Alternative Unit')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header p-3">
                        <h5 class="mb-0">{{ isset($alternative_unit) ? 'Edit' : 'Create' }} Alternative Unit</h5>
                    </div>
                    <div class="card-body p-3 pt-0">
                        <form id="alternativeUnitForm" method="POST" action="{{ isset($alternative_unit) ? route('alternative-units.update', $alternative_unit) : route('alternative-units.store') }}">
                            @csrf
                            @if(isset($alternative_unit))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Alternative Unit Name</label>
                                <input type="text" name="name" class="form-control form-control-sm"
                                       value="{{ old('name', $alternative_unit->name ?? '') }}" required
                                       data-input-index="0">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control form-control-sm" rows="3"
                                          data-input-index="1">{{ old('description', $alternative_unit->description ?? '') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select form-select-sm" data-input-index="2">
                                    <option value="1" {{ old('status', $alternative_unit->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $alternative_unit->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-sm btn-primary" id="submitButton">
                                    <i class="fas fa-save me-1"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('alternativeUnitForm');
            // Get only input fields (name, description, status) - exclude submit button
            const inputFields = Array.from(form.querySelectorAll(
                'input[name="name"], textarea[name="description"], select[name="status"]'
            ));

            // Sort elements by their data-input-index
            inputFields.sort((a, b) => {
                return parseInt(a.getAttribute('data-input-index')) - parseInt(b.getAttribute('data-input-index'));
            });

            // Handle keyboard navigation for input fields
            inputFields.forEach(input => {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        moveToNextField(input);
                    }
                    else if (e.key === 'Escape') {
                        e.preventDefault();
                        moveToPrevField(input);
                    }
                });
            });

            function moveToNextField(currentField) {
                const currentIndex = inputFields.indexOf(currentField);
                const nextIndex = (currentIndex + 1) % inputFields.length; // Wrap around to first field
                inputFields[nextIndex].focus();
            }

            function moveToPrevField(currentField) {
                const currentIndex = inputFields.indexOf(currentField);
                const prevIndex = (currentIndex - 1 + inputFields.length) % inputFields.length; // Wrap around to last field
                inputFields[prevIndex].focus();
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .form-control-sm, .form-select-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.875rem;
        }
        @media (max-width: 768px) {
            .col-md-8 {
                width: 100%;
            }
        }
    </style>
@endpush
