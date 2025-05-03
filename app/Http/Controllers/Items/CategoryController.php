<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('dashboard.pages.components.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.pages.components.categories.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('dashboard.pages.components.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        try {
            // Debug output shows status is boolean true
            // So we should check for boolean true, not == 1
            if ($category->status === true) {
                return redirect()->route('categories.index')
                    ->with('error', 'Cannot delete: Category is currently active.');
            }

            // Check if category is used by any items
            if (Item::where('default_category_id', $category->id)->exists()) {
                return redirect()->route('categories.index')
                    ->with('error', 'Cannot delete: Category is being used by items.');
            }

            // Proceed with deletion if not active and not used
            $category->delete();

            return redirect()->route('categories.index')
                ->with('success', 'Category deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->route('categories.index')
                ->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
