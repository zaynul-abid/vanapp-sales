<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return view('dashboard.pages.components.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('dashboard.pages.components.departments.forms');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully');
    }

    public function show(Department $department)
    {
        return view('dashboard.pages.components.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('dashboard.pages.components.departments.forms', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,'.$department->id,
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully');
    }
}
