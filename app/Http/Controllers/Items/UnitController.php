<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemUnitDetail;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->get();
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
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
    public function showUnit($id){
        $changedUnits = ItemUnitDetail::paginate(10);
        $units=Unit::all();
        $unitItem = Item::findOrFail($id);
       return view('dashboard.pages.components.items.change-unit', compact('unitItem','units','changedUnits'));

    }

    public function createUnit(Request $request, $id){

      $data=[
          'default_item_id'=>$id,
          'name'=>$request->name,
          'unit_name'=>$request->unit_name,
          'quantity'=> $request->quantity,
          'tax_percentage'=>$request->tax_percentage,
          'wholesale_price'=>$request->wholesale_price,
          'retail_price'=>$request->retail_price,
          'stock'=>$request->current_stock,
          'type'=>'secondary',
      ] ;
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
