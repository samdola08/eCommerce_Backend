<?php

namespace App\Http\Controllers\API\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock\Stock;
use App\Models\Order\OrderDelivery;
use App\Models\Order\OrderDeliveryItem;
use Illuminate\Support\Facades\DB;

class StockApiController extends Controller
{
    public function index()
    {
        $stocks = Stock::with([
            'product:id,name,img',
            'warehouse:id,name'
        ])->latest()->get();

        return response()->json($stocks);
    }

    public function store(Request $request)
    {
        $stock = Stock::create([
            'product_id'    => $request->product_id,
            'warehouse_id'  => $request->warehouse_id,
            'type'          => $request->type,
            'reference_id'  => $request->reference_id,
            'quantity_in'   => $request->quantity_in ?? 0,
            'quantity_out'  => $request->quantity_out ?? 0,
            'stock_date'    => $request->stock_date ?? now(),
            'note'          => $request->note,
        ]);

        return response()->json(['message' => 'Stock added', 'data' => $stock], 201);
    }

    public function getCurrentStock($productId, $warehouseId)
    {
        $stock = Stock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->selectRaw('COALESCE(SUM(quantity_in),0) - COALESCE(SUM(quantity_out),0) AS current_stock')
            ->value('current_stock');

        return response()->json([
            'product_id'    => $productId,
            'warehouse_id'  => $warehouseId,
            'current_stock' => (int) $stock,
        ]);
    }

    public function storeDelivery(Request $request)
    {
        $deliveryData = [
            'order_id'         => $request->order_id,
            'warehouse_id'     => $request->warehouse_id,
            'delivery_person'  => $request->delivery_person,
            'delivery_company' => $request->delivery_company ?? 'N/A',
            'delivery_note'    => $request->delivery_note ?? '',
            'delivery_status'  => $request->delivery_status,
            'delivery_date'    => now(),
        ];

        $items = $request->items;

        $delivery = DB::transaction(function () use ($deliveryData, $items) {
            $delivery = OrderDelivery::create($deliveryData);

            foreach ($items as $item) {
                OrderDeliveryItem::create([
                    'delivery_id' => $delivery->id,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                ]);

                Stock::create([
                    'warehouse_id' => $deliveryData['warehouse_id'],
                    'product_id'   => $item['product_id'],
                    'type'         => 'sale',
                    'reference_id' => $delivery->order_id,
                    'quantity_in'  => 0,
                    'quantity_out' => $item['quantity'],
                    'stock_date'   => now(),
                    'note'         => 'Delivery for order #' . $delivery->order_id,
                ]);
            }

            return $delivery;
        });

        return response()->json([
            'message' => 'Delivery created & stock updated successfully.',
            'data'    => $delivery->load('items'),
        ], 201);
    }
}
