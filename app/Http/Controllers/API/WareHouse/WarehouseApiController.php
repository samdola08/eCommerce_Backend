<?php

namespace App\Http\Controllers\API\WareHouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WareHouse\Warehouse;

class WarehouseApiController extends Controller
{
     public function index()
    {
        return response()->json(['warehouses' => Warehouse::all()]);
    }

    public function store(Request $request)
    {
        $warehouse            = new Warehouse();
        $warehouse->name      = $request->name;
        $warehouse->location  = $request->location;
        $warehouse->save();

        return response()->json(['success' => true, 'warehouse' => $warehouse]);
    }

    /* ─────────────────────────────
       GET /api/warehouses/{id}
       ───────────────────────────── */
    public function show(string $id)
    {
        $warehouse = Warehouse::find($id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }

        return response()->json($warehouse);
    }

    public function update(Request $request, string $id)
    {
        $warehouse = Warehouse::find($id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }

        $warehouse->name     = $request->name;
        $warehouse->location = $request->location;
        $warehouse->save();

        return response()->json(['success' => true, 'updated_warehouse' => $warehouse]);
    }

  
    public function destroy(string $id)
    {
        $warehouse = Warehouse::find($id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }

        $warehouse->delete();
        return response()->json(['success' => true, 'deleted_id' => $id]);
    }
}
