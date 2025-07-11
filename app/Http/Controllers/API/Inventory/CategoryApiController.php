<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Category;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    public function index()
    {
        return response()->json(['categories' => Category::all()]);
    }

    public function store(Request $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        if ($request->hasFile('image')) {
            $imageName = $category->id . '.' . $request->image->extension();
            $uploadPath = public_path('img/Category');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $request->image->move($uploadPath, $imageName);
            $category->image = $imageName;
            $category->save();
        }

        return response()->json(['success' => true, 'category' => $category]);
    }

    public function show(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json(['category' => $category]);
    }

    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->name = $request->name;
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            // পুরনো ইমেজ ডিলিট
            if ($category->image && file_exists(public_path('img/Category/' . $category->image))) {
                unlink(public_path('img/Category/' . $category->image));
            }

            $imageName = $category->id . '.' . $request->image->extension();
            $request->image->move(public_path('img/Category'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return response()->json(['success' => true, 'updated_category' => $category]);
    }

    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        if ($category->image && file_exists(public_path('img/Category/' . $category->image))) {
            unlink(public_path('img/Category/' . $category->image));
        }

        $category->delete();
        return response()->json(['success' => true, 'deleted_id' => $id]);
    }
}
