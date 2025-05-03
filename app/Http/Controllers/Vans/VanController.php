<?php

namespace App\Http\Controllers\Vans;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Van;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VanController extends Controller
{
    public function index()
    {
        $vans = Van::latest()->paginate(10);
        return view('dashboard.pages.components.vans.index', compact('vans'));
    }

    public function create()
    {
        return view('dashboard.pages.components.vans.forms');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'register_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Za-z0-9\-]+$/',
                Rule::unique('vans')
            ],
            'status' => 'boolean',
        ]);

        // Convert to uppercase for consistency
        $validated['register_number'] = strtoupper($validated['register_number']);

        Van::create($validated);

        return redirect()->route('vans.index')
            ->with('success', 'Van created successfully.');
    }

    public function show(Van $van)
    {
        return view('dashboard.pages.components.vans.show', compact('van'));
    }

    public function edit(Van $van)
    {
        return view('dashboard.pages.components.vans.forms', compact('van'));
    }

    public function update(Request $request, Van $van)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'register_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Za-z0-9\-]+$/',
                Rule::unique('vans')->ignore($van->id)
            ],
            'status' => 'boolean',
        ]);

        $validated['register_number'] = strtoupper($validated['register_number']);
        $van->update($validated);

        return redirect()->route('vans.index')->with('success', 'Van updated successfully');
    }

    public function destroy(Van $van)
    {
        // Check if status is 0 and employee_id is null
        if ($van->status == 0 && $van->employee_id === null) {
            $van->delete();
            return redirect()->route('vans.index')
                ->with('success', 'Van deleted successfully');
        }

        // If conditions are not met, return with an error message
        return redirect()->route('vans.index')
            ->with('error', 'Van cannot be deleted because it is either assigned to an employee or has an active status.');
    }



    public function showSelection()
    {
        $vans = Van::with('employee')->paginate(10); // 10 items per page
        $employees = Employee::doesntHave('van')->get();
        return view('dashboard.pages.components.vans.van-selection', compact('vans', 'employees'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'van_id' => 'required|exists:vans,id',
            'employee_id' => 'required|exists:employees,id'
        ]);

        // First, unassign the van if it's already assigned
        $van = Van::find($request->van_id);
        if ($van->employee_id) {
            $van->update(['employee_id' => null, 'status' => 'available']);
        }

        // Assign the van to the new employee
        $van->update([
            'employee_id' => $request->employee_id,
            'status' => 'assigned'
        ]);

        return redirect()->back()->with('success', 'Van assigned successfully!');
    }


    public function unassign($vanId)
    {
        $van = Van::findOrFail($vanId);
        $van->update(['employee_id' => null, 'status' => 'available']);

        return redirect()->back()->with('success', 'Van unassigned successfully!');
    }
}
