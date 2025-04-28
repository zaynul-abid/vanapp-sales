<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('dashboard.pages.components.customers.index',compact('customers'));
    }

    public function create()
    {
        return view('dashboard.pages.components.customers.forms');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone' => 'required|numeric',
            'address' => 'required|string',
            'customer_type' => 'required|string',
            'is_active' => 'boolean'
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }
    public function edit(Customer $customer){
        return view('dashboard.pages.components.customers.forms',compact('customer'));
    }
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,'.$customer->id,
            'phone' => 'required|numeric',
            'address' => 'required|string',
            'customer_type' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

//    public function getCustomer(Request $request)
//    {
//        $name = $request->input('name');
//
//        $customers = Customer::where('name', 'LIKE', '%' . $name . '%')
//            ->where('is_active', 1)
//            ->limit(10)
//            ->get();
//
//        $results = [];
//
//        foreach ($customers as $customer) {
//            $results[] = [
//                'id' => $customer->customer_id,
//                'label' => $customer->name,
//                'value' => $customer->name, // What gets inserted into the input box
//                'address' => $customer->address,
//            ];
//        }
//
//        return response()->json($results);
//    }

    public function searchCustomers(Request $request)
    {
        $query = $request->input('query');

        $customers = Customer::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->where('is_active', 1)
            ->select('id','customer_id', 'name', 'address', 'phone', 'email')
            ->limit(10)
            ->get();

        return response()->json($customers);
    }

}
