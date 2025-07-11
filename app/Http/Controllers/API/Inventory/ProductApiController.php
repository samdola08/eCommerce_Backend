<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class ProductApiController extends Controller
{
    public function index()
    {
        return response()->json(['products' => Product::all()]);
    }


    public function store(Request $request)
    {
        // \Log::info('Store method called.');
        // \Log::info('Category ID from request: ' . $request->category_id);
        DB::beginTransaction();

        try {
            $product               = new Product();
            $product->name         = $request->name;
            $product->brand_id     = $request->brand_id;
            $product->category_id  = $request->category_id;
            $product->supplier_id  = $request->supplier_id;
            $product->barcode      = $request->barcode;
            $product->price        = $request->price;
            $product->discount     = $request->discount ?? 0.00;
            $product->tax          = $request->tax ?? 0.00;
            $product->quantity     = $request->quantity ?? 0;
            $product->status       = $request->status ?? 'active';
            $product->description  = $request->description;
            $product->save();

            if ($request->hasFile('imgs')) {
                $imageNames = [];

                foreach ($request->file('imgs') as $file) {
                    $filename = $product->id . '_' . time() . '_' . $file->getClientOriginalName();

                    try {
                        $file->move(public_path('img/Product'), $filename);
                    } catch (\Exception $e) {
                        throw new \Exception("Failed to upload image: " . $file->getClientOriginalName());
                    }

                    $imageNames[] = $filename;
                }

                $product->img = json_encode($imageNames);
                $product->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Optional: log error
            Log::error('Product Store Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Product creation failed.',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // public function store(Request $request)
    // {

    //     $validated = $request->validate([
    //         'name'         => 'required|string|max:255',
    //         'brand_id'     => 'required|exists:product_brands,id',
    //         'category_id'  => 'required|exists:product_categories,id',
    //         'supplier_id'  => 'required|exists:suppliers,id',
    //         'barcode'      => 'nullable|string|max:100|unique:products,barcode',
    //         'price'        => 'required|numeric|min:0',
    //         'discount'     => 'nullable|numeric|min:0',
    //         'tax'          => 'nullable|numeric|min:0',
    //         'quantity'     => 'nullable|integer|min:0',
    //         'status'       => 'in:active,inactive',
    //         'description'  => 'nullable|string',
    //         'imgs.*'       => 'nullable|image|max:1024', // 1 MB per image
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         $product = Product::create(array_merge(
    //             $validated,
    //             ['img' => null]          // placeholder for now
    //         ));

    //         /* 3️⃣  Handle images if supplied */
    //         if ($request->hasFile('imgs')) {
    //             $filenames = [];
    //             foreach ($request->file('imgs') as $file) {
    //                 $name = $product->id . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
    //                 $file->move(public_path('img/Product'), $name);
    //                 $filenames[] = $name;
    //             }
    //             // One atomic update
    //             $product->update(['img' => json_encode($filenames)]);
    //         }

    //         DB::commit();

    //         /* 4️⃣  Return hydrated product (with relationships) */
    //         return response()->json([
    //             'success' => true,
    //             'product' => $product->load('brand', 'category', 'supplier')
    //         ]);
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         Log::error('Product Store Error: ' . $e->getMessage());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Product creation failed.',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }




    public function show($id)
    {
        $p = Product::with([
            'brand:id,name',
            'category:id,name',
            'supplier:id,name',
        ])->findOrFail($id);

        return response()->json([
            'id'          => $p->id,
            'name'        => $p->name,
            'description' => $p->description,
            'price'       => $p->price,
            'discount'    => $p->discount,
            'tax'         => $p->tax,
            'quantity'    => $p->quantity,
            'status'      => $p->status,
            'barcode'     => $p->barcode,
            'img'         => $p->img,

            /* names ready for the front‑end */
            'brand'       => $p->brand?->name,
            'category'    => $p->category?->name,
            'supplier'    => $p->supplier?->name,

            'created_at'  => $p->created_at,
            'updated_at'  => $p->updated_at,
        ]);
    }

    // public function show($id)
    // {
    //     $product = Product::with([
    //         'brand:id,name',
    //         'category:id,name',
    //         'supplier:id,name',
    //     ])->findOrFail($id);

    //     return response()->json([
    //         'id'          => $product->id,
    //         'name'        => $product->name,
    //         'description' => $product->description,
    //         'price'       => $product->price,
    //         'discount'    => $product->discount,
    //         'tax'         => $product->tax,
    //         'quantity'    => $product->quantity,
    //         'status'      => $product->status,
    //         'barcode'     => $product->barcode,
    //         'img'         => $product->img,

    //         'brand'       => $product->brand?->name ?? null,
    //         'category'    => $product->category?->name ?? null,
    //         'supplier'    => $product->supplier?->name ?? null,

    //         'created_at'  => $product->created_at,
    //         'updated_at'  => $product->updated_at,
    //     ]);
    // }




    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Update basic fields
        $product->name         = $request->name         ?? $product->name;
        $product->brand_id     = $request->brand_id     ?? $product->brand_id;
        $product->category_id = $request->category_id ?? $product->category_id;
        $product->supplier_id  = $request->supplier_id  ?? $product->supplier_id;
        $product->barcode      = $request->barcode      ?? $product->barcode;
        $product->price        = $request->price        ?? $product->price;
        $product->discount     = $request->discount     ?? $product->discount;
        $product->tax          = $request->tax          ?? $product->tax;
        $product->quantity     = $request->quantity     ?? $product->quantity;
        $product->status       = $request->status       ?? $product->status;
        $product->description  = $request->description  ?? $product->description;

        // Decode existing images JSON array
        $existingImages = [];
        if ($product->img) {
            $existingImages = json_decode($product->img, true);
            if (!is_array($existingImages)) {
                $existingImages = [$product->img];
            }
        }

        // Remove images that frontend wants deleted
        $removeImages = $request->input('remove_imgs', []);
        foreach ($removeImages as $rmImg) {
            if (($key = array_search($rmImg, $existingImages)) !== false) {
                // Delete physical file
                $path = public_path('img/Product/' . $rmImg);
                if (file_exists($path)) {
                    unlink($path);
                }
                // Remove from array
                unset($existingImages[$key]);
            }
        }
        // Re-index the array
        $existingImages = array_values($existingImages);

        // Handle newly uploaded images (multiple)
        if ($request->hasFile('imgs')) {
            foreach ($request->file('imgs') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('img/Product'), $filename);
                $existingImages[] = $filename;
            }
        }

        // Save updated images list as JSON string
        $product->img = json_encode($existingImages);

        $product->save();

        return response()->json(['success' => true, 'updated_product' => $product]);
    }


    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['success' => true, 'deleted_id' => $id]);
    }


    public function find($id)
    {
        $product = Product::find($id);
        return response()->json(['product' => $product]);
    }
}
