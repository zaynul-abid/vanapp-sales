<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Models\AlternateUnit;
use App\Models\Item;
use App\Models\ItemUnitDetail;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->paginate(10);
        return view('dashboard.pages.components.units.index', compact('units'));
    }

    public function create()
    {
        return view('dashboard.pages.components.units.forms');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        Unit::create($request->all());

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    public function show(Unit $unit)
    {
        return view('', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        return view('dashboard.pages.components.units.forms', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $unit->update($request->all());

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        try {
            // Check if unit is active
            if ($unit->status) {
                return back()->with('error', 'Cannot delete: Unit is currently active.');
            }

            // Check if unit is referenced in items
            if (Item::where('default_unit_id', $unit->id)->exists()) {
                return back()->with('error', 'Cannot delete: Unit is assigned to items.');
            }

            $unit->delete();
            return back()->with('success', 'Unit deleted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
    public function showUnit($id)
    {
        $changedUnits = ItemUnitDetail::where('default_item_id', $id)->paginate(10);
        $alternateUnits = AlternateUnit::all(); // Fetch from alternate_units
        $unitItem = Item::findOrFail($id);
        return view('dashboard.pages.components.items.change-unit', compact('unitItem', 'alternateUnits', 'changedUnits'));
    }

    public function createUnit(Request $request, $id)
    {
        $request->validate([
            'unit_name' => 'required|string|max:255|exists:alternate_units,name', // Validate against alternate_units
            'quantity' => 'required|numeric|min:1',
            'wholesale_price' => 'required|numeric|min:1',
            'retail_price' => 'required|numeric|min:1',
            'name' => 'required|string|max:255',
            'tax_percentage' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'base_unit_id' => 'nullable|exists:units,id',
        ]);

        $data = [
            'default_item_id' => $id,
            'name' => $request->name,
            'unit_name' => $request->unit_name,
            'quantity' => $request->quantity,
            'tax_percentage' => $request->tax_percentage,
            'wholesale_price' => $request->wholesale_price,
            'retail_price' => $request->retail_price,
            'stock' => $request->current_stock,
            'type' => 'secondary',
        ];

        ItemUnitDetail::create($data);
        return redirect()->route('items.index')->with('success', 'Unit added successfully.');
    }

    public function deleteUnit($id)
    {
        try {
            $itemUnitDetail = ItemUnitDetail::findOrFail($id);
            $itemUnitDetail->delete();

            return redirect()->back()->with('success', 'Unit conversion deleted successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Unit conversion not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the unit conversion.');
        }
    }

}
