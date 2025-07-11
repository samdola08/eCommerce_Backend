<?php

namespace App\Http\Controllers\API\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier\Supplier;

class SupplierApiController extends Controller
{
    public function index()
    {
        return response()->json(['suppliers' => Supplier::all()]);
    }

    public function store(Request $request)
    {
        $supplier = new Supplier();
        $supplier->name         = $request->name;
        $supplier->phone        = $request->phone;
        $supplier->email        = $request->email;
        $supplier->address      = $request->address;
        $supplier->company_name = $request->company_name;
        $supplier->save();

        return response()->json(['success' => true, 'supplier' => $supplier]);
    }

    public function show(string $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }

        return response()->json($supplier);
    }

    public function update(Request $request, string $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }

        $supplier->name         = $request->name;
        $supplier->phone        = $request->phone;
        $supplier->email        = $request->email;
        $supplier->address      = $request->address;
        $supplier->company_name = $request->company_name;
        $supplier->save();

        return response()->json(['success' => true, 'updated_supplier' => $supplier]);
    }

    public function destroy(string $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }

        $supplier->delete();

        return response()->json(['success' => true, 'deleted_id' => $id]);
    }
}
