<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer\Customer;

class CustomerApiController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    // Show single customer by ID
    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer);
    }

    // Create a new customer
    public function store(Request $request)
    {
      

        $customer = Customer::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'status'  => $request->status ?? 'active',
        ]);

        return response()->json($customer, 201);
    }

    // Update an existing customer
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

      

        $customer->update($request->only(['name', 'email', 'address',"phone", 'status']));

        return response()->json($customer);
    }

    // Delete a customer
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
