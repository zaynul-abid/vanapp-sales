<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('department')->paginate(10);
        return view('dashboard.pages.components.employee.index', compact('employees'));
    }
    public function create()
    {
        $departments = Department::active()->get();
        return view('dashboard.pages.components.employee.forms', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees,email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'required',
            'address' => 'required',
            'position' => 'required',
            'status' => 'required|in:active,inactive',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $employee = Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
            'department_id' => $request->department_id,
            'status' => $request->status,
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 'employee',
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }


    public function show(Employee $employee)
    {
        return view('dashboard.pages.components.employee.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::active()->get();
        return view('dashboard.pages.components.employee.forms', compact('employee', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:employees,email,{$id}",
            'phone' => 'required',
            'address' => 'required',
            'position' => 'required',
            'status' => 'required|in:active,inactive',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
            'department_id' => $request->department_id,
            'status' => $request->status,
        ]);

        $user = User::where('email', $employee->email)->first();
        if ($user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }


    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $user = User::where('email', $employee->email)->first();

        $employee->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
