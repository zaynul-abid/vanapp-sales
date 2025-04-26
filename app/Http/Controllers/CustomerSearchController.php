<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerSearchController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->search;

        $customers = Customer::where('name', 'LIKE', "%{$search}%")
            ->orWhere('address', 'LIKE', "%{$search}%")
            ->limit(5)
            ->get();

        return response()->json($customers);
    }
}
