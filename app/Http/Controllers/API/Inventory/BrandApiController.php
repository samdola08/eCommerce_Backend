<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Brand;

class BrandApiController extends Controller
{
    // List all brands
    public function index()
    {
        return response()->json(['brands' => Brand::all()]);
    }

    // Create a new brand
    public function store(Request $request)
    {
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->description = $request->description;
        $brand->category_id = $request->category_id;
        $brand->save();

        // If image uploaded
        if ($request->hasFile('image')) {
            $imageName = $brand->id . '.' . $request->image->extension();
            $request->image->move(public_path('img/Brand'), $imageName);
            $brand->image = $imageName;
            $brand->save();
        }

        return response()->json(['success' => true, 'brand' => $brand]);
    }

        public function show($id)
        {
            $brand = Brand::with('category')->find($id);
            if (!$brand) {
                return response()->json(['error' => 'Brand not found'], 404);
            }
            return response()->json(['brand' => $brand]);
        }

    // Update an existing brand
    public function update(Request $request, string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json(['error' => 'Brand not found'], 404);
        }

        $brand->name = $request->name ?? $brand->name;
        $brand->description = $request->description ?? $brand->description;
        $brand->category_id = $request->category_id ?? $brand->category_id;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($brand->image && file_exists(public_path('img/Brand/' . $brand->image))) {
                unlink(public_path('img/Brand/' . $brand->image));
            }
            $imageName = $brand->id . '.' . $request->image->extension();
            $request->image->move(public_path('img/Brand'), $imageName);
            $brand->image = $imageName;
        }

        $brand->save();

        return response()->json(['success' => true, 'updated_brand' => $brand]);
    }

    // Delete a brand
    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json(['error' => 'Brand not found'], 404);
        }

        // Delete image if exists
        if ($brand->image && file_exists(public_path('img/Brand/' . $brand->image))) {
            unlink(public_path('img/Brand/' . $brand->image));
        }

        $brand->delete();
        return response()->json(['success' => true, 'deleted_id' => $id]);
    }

    // Alternative find route (if needed)
    public function find($id)
    {
        $brand = Brand::find($id);
        return response()->json(['brand' => $brand]);
    }
}
