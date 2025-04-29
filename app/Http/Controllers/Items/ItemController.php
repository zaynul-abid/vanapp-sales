<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemUnitDetail;
use App\Models\Tax;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category', 'unit', 'tax'])->latest()->get();
        return view('dashboard.pages.components.items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->get();
        $units = Unit::where('status', 1)->get();
        $taxes = Tax::all();
        return view('dashboard.pages.components.items.forms', compact('categories', 'units', 'taxes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'default_category_id' => 'required|exists:categories,id',
            'default_unit_id' => 'required|exists:units,id',
            'tax_id' => 'nullable|exists:taxes,id',
            'purchase_price' => 'required|numeric',
            'wholesale_price' => 'required|numeric',
            'retail_price' => 'required|numeric',
            'opening_stock' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
        ]);

        $data = $request->only([
            'name', 'default_category_id', 'default_unit_id', 'tax_id',
            'purchase_price', 'wholesale_price', 'retail_price',
            'opening_stock', 'status'
        ]);

        // Set current_stock equal to opening_stock
        $data['current_stock'] = $request->input('opening_stock');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        $unit = Unit::find($request->default_unit_id);
        $tax = Tax::find($request->tax_id);

        $itemUnitDetails = [
            'default_item_id' => $item->id,
            'name' => $request->input('name'),
            'unit_name' => $unit->name,
            'quantity' => '1',
            'tax_percentage' => $tax ? $tax->tax_percentage : 0,
            'wholesale_price' => $request->input('wholesale_price'),
            'retail_price' => $request->input('retail_price'),
            'stock' => $data['current_stock'],
            'type' => 'primary',
        ];
        ItemUnitDetail::create($itemUnitDetails);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function show(Item $item)
    {
        return view('', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::where('status', 1)->get();
        $units = Unit::where('status', 1)->get();
        $taxes = Tax::all();
        return view('dashboard.pages.components.items.forms', compact('item', 'categories', 'units', 'taxes'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'default_category_id' => 'required|exists:categories,id',
            'default_unit_id' => 'required|exists:units,id',
            'tax_id' => 'nullable|exists:taxes,id',
            'purchase_price' => 'required|numeric',
            'wholesale_price' => 'required|numeric',
            'retail_price' => 'required|numeric',
            'opening_stock' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
        ]);

        $data = $request->only([
            'name', 'default_category_id', 'default_unit_id', 'tax_id',
            'purchase_price', 'wholesale_price', 'retail_price',
            'opening_stock', 'status'
        ]);

        // Add new opening_stock to existing current_stock
        $data['current_stock'] = $item->current_stock + $request->input('opening_stock');

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        // Update or create the ItemUnitDetail record
        $unit = Unit::find($request->default_unit_id);
        $tax = Tax::find($request->tax_id);

        $itemUnitDetails = [
            'default_item_id' => $item->id,
            'name' => $request->input('name'),
            'unit_name' => $unit->name,
            'quantity' => '1',
            'tax_percentage' => $tax ? $tax->tax_percentage : 0,
            'wholesale_price' => $request->input('wholesale_price'),
            'retail_price' => $request->input('retail_price'),
            'stock' => $data['current_stock'],
            'type' => 'primary',
        ];

        // Update if exists, otherwise create
        $itemUnitDetail = ItemUnitDetail::where('default_item_id', $item->id)
            ->where('type', 'primary')
            ->first();

        if ($itemUnitDetail) {
            $itemUnitDetail->update($itemUnitDetails);
        } else {
            ItemUnitDetail::create($itemUnitDetails);
        }

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        DB::transaction(function () use ($item) {
            ItemUnitDetail::where('default_item_id', $item->id)->delete();

            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $item->delete();
        });

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }


    public function searchItems(Request $request)
    {
        $query = $request->input('query'); // Changed from 'term' to 'query'

        $items = DB::table('item_unit_details')
            ->where('name', 'LIKE', '%'.$query.'%')
            ->orWhere('id', 'LIKE', '%'.$query.'%') // Also search by ID
            ->select(
                'id',
                'name',
                'unit_name',
                'quantity',
                'tax_percentage',
                'retail_price',
                'wholesale_price'
            )
            ->limit(10)
            ->get();

        return response()->json($items);
    }
}
