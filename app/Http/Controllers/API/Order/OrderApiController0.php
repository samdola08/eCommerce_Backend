<?php

namespace App\Http\Controllers\API\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
// use App\models\Order\Order;
use App\models\Order\OrderItem;
use App\models\Order\OrderPayment;
use App\models\Order\OrderShipment;
use App\models\Order\OrderStatusHistory;
use Illuminate\Support\Facades\DB;
use App\Models\OrderDelivery;

class OrderApiController extends Controller
{
    public function index()
    {
        $orders = Order::withCount('items')
            ->latest('order_date')
            ->paginate(15);

        return response()->json($orders);
    }

    /* ───────────────────────────── SHOW ────────────────────────────── */
    public function show($id)
    {
        $order = Order::with(['items', 'payments', 'shipments', 'statuses'])
            ->findOrFail($id);

        return response()->json($order);
    }

    /* ───────────────────────────── STORE ───────────────────────────── */
    public function store(Request $req)
    {
        $data = $req->all();                          // কোনো validation নয়

        $order = DB::transaction(function () use ($data) {

            /* ▸ Header ------------------------------------------------ */
            $orderNo = 'ORD-' . now()->format('ymdHis') . rand(100, 999);

            $order = Order::create([
                'customer_id'      => $data['customer_id']          ?? null,
                'order_no'         => $orderNo,
                'order_date'       => $data['order_date']           ?? now(),
                'delivery_date'    => $data['delivery_date']        ?? now(),
                'shipping_address' => $data['shipping_address']     ?? '',
                'discount_amount'  => $data['discount_amount']      ?? 0,
                'vat_amount'       => $data['vat_amount']           ?? 0,
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'total_amount'     => 0,
                'paid_amount'      => 0,
                'due_amount'       => 0,
            ]);

            /* ▸ Items ------------------------------------------------- */
            $total = 0;
            foreach ($data['items'] ?? [] as $itm) {
                $sub = ($itm['unit_price'] ?? 0) * ($itm['quantity'] ?? 0)
                    - ($itm['discount']   ?? 0)
                    + ($itm['tax']        ?? 0);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $itm['product_id'] ?? null,
                    'quantity'   => $itm['quantity']    ?? 0,
                    'unit_price' => $itm['unit_price']  ?? 0,
                    'discount'   => $itm['discount']    ?? 0,
                    'tax'        => $itm['tax']         ?? 0,
                    'subtotal'   => $sub,
                ]);

                $total += $sub;
            }

            /* ▸ Payments (optional) ---------------------------------- */
            $paid  = 0;
            foreach ($data['payments'] ?? [] as $pay) {
                OrderPayment::create([
                    'order_id'     => $order->id,
                    'payment_date' => $pay['payment_date'] ?? now(),
                    'amount'       => $pay['amount']       ?? 0,
                    'method'       => $pay['method']       ?? 'cash',
                    'note'         => $pay['note']         ?? null,
                ]);
                $paid += $pay['amount'] ?? 0;
            }

            /* ▸ Final totals & status -------------------------------- */
            $due  = $total - $paid;
            $order->update([
                'total_amount'   => $total + $order->vat_amount - $order->discount_amount,
                'paid_amount'    => $paid,
                'due_amount'     => $due,
                'payment_status' => $paid == 0 ? 'unpaid' : ($due == 0 ? 'paid' : 'partial'),
            ]);

            /* ▸ Initial status history -------------------------------- */
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'old_status' => null,
                'new_status' => 'pending',
            ]);

            return $order;
        });

        return response()->json($order->load('items', 'payments', 'statuses'), 201);
    }

    /* ───────────────────────────── UPDATE ─────────────────────────── */
    public function update(Request $req, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($req->only([
            'delivery_date',
            'shipping_address',
            'discount_amount',
            'vat_amount'
        ]));

        return response()->json($order);
    }

    /* ───────────────────────────── DESTROY ────────────────────────── */
    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return response()->json(['message' => 'Order deleted']);
    }

    /* ─────────────────────────── addPayment ───────────────────────── */
    public function addPayment(Request $req, $id)
    {
        $order = Order::findOrFail($id);
        $data  = $req->all();

        DB::transaction(function () use ($order, $data) {
            OrderPayment::create([
                'order_id'     => $order->id,
                'payment_date' => $data['payment_date'] ?? now(),
                'amount'       => $data['amount']       ?? 0,
                'method'       => $data['method']       ?? 'cash',
                'note'         => $data['note']         ?? null,
            ]);

            $order->paid_amount   += $data['amount'] ?? 0;
            $order->due_amount     = $order->total_amount - $order->paid_amount;
            $order->payment_status = $order->due_amount == 0 ? 'paid' : 'partial';
            $order->save();
        });

        return response()->json($order->load('payments'));
    }

    /* ─────────────────────── changeStatus ─────────────────────────── */
    public function changeStatus(Request $req, $id)
    {
        $order      = Order::findOrFail($id);
        $newStatus  = $req->input('new_status');
        $oldStatus  = $order->status;

        if (!$newStatus || $newStatus === $oldStatus) {
            return response()->json(['message' => 'Status unchanged'], 422);
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            $order->update(['status' => $newStatus]);

            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);
        });

        return response()->json($order->load('statuses'));
    }

    /* ─────────────────────── addShipment ──────────────────────────── */
    public function addShipment(Request $req, $id)
    {
        $data = $req->all();

        $shipment = OrderShipment::create([
            'order_id'        => $id,
            'carrier'         => $data['carrier']         ?? null,
            'tracking_number' => $data['tracking_number'] ?? null,
            'status'          => $data['status']          ?? 'pending',
            'notes'           => $data['notes']           ?? null,
            'shipment_date'   => $data['shipment_date']   ?? now(),
        ]);

        return response()->json($shipment, 201);
    }
    public function addDelivery(Request $request, $orderId)
    {
        $data = $request->all();
        $delivery = OrderDelivery::updateOrCreate(
            ['order_id' => $orderId],
            [
                'delivery_person' => $data['delivery_person'] ?? null,
                'delivery_date' => $data['delivery_date'] ?? null,
                'delivery_status' => $data['delivery_status'] ?? 'pending',
                'delivery_notes' => $data['delivery_notes'] ?? null,
                'proof_of_delivery' => $data['proof_of_delivery'] ?? null,
            ]
        );

        return response()->json($delivery, 201);
    }

    public function getDelivery($orderId)
    {
        $delivery = OrderDelivery::where('order_id', $orderId)->first();

        if (!$delivery) {
            return response()->json(['message' => 'Delivery info not found'], 404);
        }

        return response()->json($delivery);
    }
}
