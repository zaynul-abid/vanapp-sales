<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tax;
use App\Models\Unit;
use Illuminate\Http\Request;

class LoggedEmployeeController extends Controller
{
    public function createItem()
    {
        $categories = Category::where('status', 1)->get();
        $units = Unit::where('status', 1)->get();
        $taxes = Tax::all();
      return view('dashboard.pages.employee.create-item', compact('categories', 'units', 'taxes'));
    }
}
