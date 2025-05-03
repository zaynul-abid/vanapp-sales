@extends('dashboard.layouts.app')
@section('title', isset($employee) ? 'Edit Employee' : 'Create Employee')

@section('navbar')
    @if(auth()->user()->isSuperAdmin())
        @include('dashboard.partials.sidebar.superadmin-sidebar')
    @elseif(auth()->user()->isAdmin())
        @include('dashboard.partials.sidebar.admin-sidebar')
    @endif
@endsection

@section('content')
    <div class="container py-3">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">{{ isset($employee) ? 'Edit Employee' : 'Create Employee' }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ isset($employee) ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST" id="employeeForm">
                    @csrf
                    @if(isset($employee))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $employee->name ?? '') }}" required>
                                @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $employee->email ?? '') }}" required>
                                @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $employee->phone ?? '') }}" required>
                                @error('phone')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $employee->address ?? '') }}" required>
                                @error('address')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" class="form-control" id="position" name="position" value="{{ old('position', $employee->position ?? '') }}" required>
                                @error('position')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="department_id" class="form-label">Department</label>
                                <select class="form-select" id="department_id" name="department_id" required>
                                    <option value="" disabled {{ old('department_id', $employee->department_id ?? '') ? '' : 'selected' }}>Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" {{ old('status', $employee->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $employee->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password {{ isset($employee) ? '(Leave blank to keep unchanged)' : '' }}</label>
                                <input type="password" class="form-control" id="password" name="password" {{ isset($employee) ? '' : 'required' }}>
                                @error('password')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-outline-secondary" id="submitButton">{{ isset($employee) ? 'Update' : 'Create' }} Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Enter and Escape key navigation -->
    <script>
        document.getElementById('employeeForm').addEventListener('keydown', function(event) {
            const inputs = Array.from(this.querySelectorAll('input, select')); // Get all input and select elements
            const currentIndex = inputs.indexOf(document.activeElement); // Get index of focused element

            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent form submission
                if (currentIndex < inputs.length - 1) { // Check if not the last input
                    inputs[currentIndex + 1].focus(); // Move to next input
                }
            } else if (event.key === 'Escape') {
                event.preventDefault(); // Prevent default Escape behavior
                if (currentIndex > 0) { // Check if not the first input
                    inputs[currentIndex - 1].focus(); // Move to previous input
                }
            }
        });
    </script>
@endsection
