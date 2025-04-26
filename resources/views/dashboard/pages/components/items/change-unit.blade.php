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
    <div class="min-h-screen bg-gray-100 text-gray-900 p-6">
        <div class="container mx-auto max-w-4xl">
            <!-- Conversion Form Card -->
            <div class="bg-white rounded-xl shadow-xl p-6 mb-8 border border-blue-200 transform hover:scale-105 transition-transform duration-300">
                <h2 class="text-2xl font-bold text-blue-600 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m-16 6h12m0 0l-4-4m4 4l-4 4"></path>
                    </svg>
                    Unit Conversion
                </h2>

                <form action="{{ route('item.create-unit', $unitItem->id) }}" method="POST" id="conversionForm">
                    @csrf
                    @method('PUT')

                    <!-- Current Unit Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <h6 class="text-blue-500 font-semibold">Item</h6>
                            <p class="text-gray-700">{{ $unitItem->name }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <h6 class="text-blue-500 font-semibold">Current Unit</h6>
                            <p class="text-gray-700">{{ $unitItem->unit->name }}</p>
                            <input type="hidden" name="base_unit_id" value="{{ $unitItem->unit->id }}">
                        </div>
                    </div>

                    <!-- Conversion Form -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="unit_name" class="block text-sm font-medium text-blue-500 mb-1">Convert To</label>
                            <select name="unit_name" id="unit_name" class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                <option value="">-- Select Unit --</option>
                                @foreach ($units as $unit)
                                    @if($unit->id != $unitItem->unit->id)
                                        <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-medium text-blue-500 mb-1">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="w-full bg-white border border-gray-300 rounded-lg p-2 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" min="1" required>
                        </div>
                    </div>

                    <input type="hidden" name="name" value="{{ $unitItem->name }}">

                    <!-- Buttons -->
                    <div class="flex justify-between">
                        <a href="{{ route('items.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-900 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create Conversion
                        </button>
                    </div>
                </form>
            </div>

            <!-- Converted Units Table -->
            <div class="bg-white rounded-xl shadow-xl p-6 border border-blue-200">
                <h3 class="text-xl font-bold text-blue-600 mb-4">Converted Units</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-700">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">#</th>
                            <th class="p-3">Name</th>
                            <th class="p-3">Unit</th>
                            <th class="p-3">Quantity</th>
                            <th class="p-3">Type</th>
                            <th class="p-3">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($changedUnits as $index => $changedunit)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="p-3">{{ $changedUnits->firstItem() + $index }}</td>
                                <td class="p-3">{{ $changedunit->name }}</td>
                                <td class="p-3">{{ $changedunit->unit_name }}</td>
                                <td class="p-3">{{ $changedunit->quantity }}</td>
                                <td class="p-3">{{ $changedunit->type }}</td>
                                <td class="p-3">
                                    <a href="{{ route('unit.item.delete', $changedunit->id) }}"
                                       class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-500 text-white rounded-lg transition-colors delete-btn"
                                       data-id="{{ $changedunit->id }}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="mt-6 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Showing {{ $changedUnits->firstItem() }} to {{ $changedUnits->lastItem() }} of {{ $changedUnits->total() }} entries
                    </div>
                    <div class="flex space-x-2">
                        {{ $changedUnits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Confirmation on Delete -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const unitId = this.dataset.id;
                    if (confirm('Are you sure you want to delete this unit conversion?')) {
                        window.location.href = this.href;
                    }
                });
            });

            // Form submission animation
            const form = document.getElementById('conversionForm');
            form.addEventListener('submit', function () {
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Processing...
                `;
            });

            // Style Laravel pagination links
            const paginationLinks = document.querySelectorAll('.pagination a');
            paginationLinks.forEach(link => {
                link.classList.add('px-3', 'py-1', 'rounded-lg', 'text-sm', 'transition-colors');
                if (link.classList.contains('active')) {
                    link.classList.add('bg-blue-600', 'text-white');
                } else {
                    link.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-blue-500', 'hover:text-white');
                }
            });
        });
    </script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles for Pagination -->
    <style>
        .pagination .page-item.active .page-link {
            background-color: #2563eb;
            color: white;
            border-color: #2563eb;
        }
        .pagination .page-item .page-link {
            background-color: #e5e7eb;
            color: #374151;
            border-color: #d1d5db;
            margin: 0 2px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .pagination .page-item .page-link:hover {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        .pagination .page-item.disabled .page-link {
            background-color: #f3f4f6;
            color: #9ca3af;
            border-color: #d1d5db;
        }
    </style>
@endsection
