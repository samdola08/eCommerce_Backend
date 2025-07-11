<?php

namespace App\Http\Controllers\API\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order\OrderDelivery;
use Illuminate\Support\Facades\DB;
use App\Models\Order\OrderItem;
use App\Models\Order\OrderDeliveryItem;
use App\Models\Stock\Stock;

class OrderDeliveryApiController extends Controller
{
    public function index()
    {
        $deliveries = OrderDelivery::with(['items', 'order', 'warehouse']) // load relationships if needed
            ->latest('delivery_date')
            ->paginate(15); // or ->get() if you want all

        return response()->json($deliveries);
    }

    public function store(Request $request, $orderId)
    {
        $delivery = DB::transaction(function () use ($request, $orderId) {
            // Create the delivery record
            $delivery = OrderDelivery::create([
                'order_id'         => $orderId,
                'warehouse_id'     => $request->warehouse_id,
                'delivery_person'  => $request->delivery_person,
                'delivery_company' => $request->delivery_company ?? 'N/A',
                'delivery_status'  => $request->delivery_status ?? 'pending',
                'delivery_note'    => $request->delivery_note ?? '',
                'delivery_date'    => $request->delivery_date ?? now()->toDateString(),
            ]);

            $items = $request->items ?? [];

            foreach ($items as $item) {
                if (empty($item['product_id']) || empty($item['quantity'])) {
                    continue;
                }

                $currentStock = Stock::where('product_id', $item['product_id'])
                    ->where('warehouse_id', $request->warehouse_id)
                    ->selectRaw('COALESCE(SUM(quantity_in),0) - COALESCE(SUM(quantity_out),0) AS total_stock')
                    ->value('total_stock');

                if ($currentStock === null) {
                    $currentStock = 0;
                }

                if ($item['quantity'] > $currentStock) {
                    throw new \Exception("Not enough stock for product #{$item['product_id']}. Available: $currentStock, Requested: {$item['quantity']}");
                }

                // ডেলিভারি আইটেম তৈরি করুন
                OrderDeliveryItem::create([
                    'delivery_id' => $delivery->id,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                ]);

                // স্টক রেকর্ড তৈরি করুন (quantity_out বাড়বে)
                Stock::create([
                    'product_id'   => $item['product_id'],
                    'warehouse_id' => $delivery->warehouse_id,
                    'type'         => 'sale',
                    'reference_id' => $orderId,
                    'quantity_in'  => 0,
                    'quantity_out' => $item['quantity'],
                    'stock_date'   => now(),
                    'note'         => 'Auto stock decrease from delivery',
                ]);
            }

            return $delivery;
        });

        return response()->json([
            'message' => 'Delivery saved and stock updated.',
            'data' => $delivery->load('items'),
        ]);
    }



    public function show($orderId)
    {
        $delivery = OrderDelivery::with('items')->where('order_id', $orderId)->first();
        if (!$delivery) {
            return response()->json(['message' => 'Delivery info not found'], 404);
        }

        return response()->json($delivery);
    }
    public function updateStatus(Request $request, $id)
    {
        $delivery = OrderDelivery::findOrFail($id);

        $validated = $request->validate([
            'delivery_status' => 'required|string|in:pending,in_transit,delivered,cancelled',
        ]);

        $delivery->delivery_status = $validated['delivery_status'];
        $delivery->save();

        return response()->json(['message' => 'Status updated', 'delivery' => $delivery], 200);
    }
}
