<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $taxes = Tax::latest()->get();
        return view('dashboard.pages.components.taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view('dashboard.pages.components.taxes.forms');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tax_percentage' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Tax::create($request->all());
        return redirect()->route('taxes.index')->with('success', 'Tax added successfully!');
    }

    public function edit(Tax $tax)
    {
        return view('dashboard.pages.components.taxes.forms', compact('tax'));
    }

    public function update(Request $request, Tax $tax)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tax_percentage' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $tax->update($request->all());
        return redirect()->route('taxes.index')->with('success', 'Tax updated successfully!');
    }

    public function destroy(Tax $tax)
    {
        $tax->delete();
        return redirect()->route('taxes.index')->with('success', 'Tax deleted successfully!');
    }
}
