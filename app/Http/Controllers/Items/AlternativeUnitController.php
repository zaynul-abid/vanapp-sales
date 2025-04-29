<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Models\AlternateUnit;
use App\Models\Unit;
use Illuminate\Http\Request;

class AlternativeUnitController extends Controller
{
    public function index()
    {
        $alternativeUnits = AlternateUnit::latest()->get();
        return view('dashboard.pages.components.units.alternative-unit-index', compact('alternativeUnits'));
    }

    public function create()
    {
        return view('dashboard.pages.components.units.alternative-unit-forms');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        AlternateUnit::create($request->all());

        return redirect()->route('alternative-units.index')->with('success', 'Unit created successfully.');
    }

    public function show(AlternateUnit $alternative_unit)
    {
        return view('', compact('alternative_unit'));
    }

    public function edit(AlternateUnit $alternative_unit)
    {
        return view('dashboard.pages.components.units.alternative-unit-forms', compact('alternative_unit'));
    }

    public function update(Request $request, AlternateUnit $alternative_unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $alternative_unit->update($request->all());

        return redirect()->route('alternative-units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy(AlternateUnit $alternative_unit)
    {
        $alternative_unit->delete();
        return redirect()->route('alternative-units.index')->with('success', 'Unit deleted successfully.');
    }
}
