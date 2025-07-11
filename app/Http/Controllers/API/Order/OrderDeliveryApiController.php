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
            // 1. Save delivery
            $delivery = OrderDelivery::create([
                'order_id'         => $orderId,
                'warehouse_id'     => $request->warehouse_id,
                'delivery_person'  => $request->delivery_person,
                'delivery_company' => $request->delivery_company ?? 'N/A',
                'delivery_status'  => $request->delivery_status ?? 'pending',
                'delivery_note'    => $request->delivery_note ?? '',
                'delivery_date'    => $request->delivery_date ?? now()->toDateString(),
            ]);

            // 2. Get ordered items
            $orderItems = OrderItem::where('order_id', $orderId)->get();

            foreach ($orderItems as $item) {
                // 3. Create delivery item
                OrderDeliveryItem::create([
                    'delivery_id' => $delivery->id,
                    'product_id'  => $item->product_id,
                    'quantity'    => $item->quantity,
                ]);

                // 4. Decrease stock
                Stock::create([
                    'product_id'   => $item->product_id,
                    'warehouse_id' => $delivery->warehouse_id,
                    'type'         => 'sale',
                    'reference_id' => $orderId,
                    'quantity_in'  => 0,
                    'quantity_out' => $item->quantity,
                    'stock_date'   => now(),
                    'note'         => 'Auto stock decrease from delivery',
                ]);
            }

            return $delivery;
        });

        return response()->json([
            'message' => 'Delivery saved and stock updated.',
            'data' => $delivery->load('items')
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
}
