<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase\Purchase;
use App\Models\Purchase\PurchaseItem;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase\PurchasePayment;

class PurchaseApiController extends Controller
{
    // List all purchases with supplier
    public function index()
    {
        return response()->json([
            'purchases' => Purchase::with('supplier')->orderBy('purchase_date', 'desc')->get()
        ]);
    }

    // Store new purchase
 public function store(Request $req)
{

       logger()->info('Purchase request data:', $req->all());
    // Basic validation
    foreach (['supplier_id', 'warehouse_id', 'purchase_date'] as $field) {
        if (empty($req->$field)) {
            return response()->json(['success' => false, 'message' => "$field is required"], 400);
        }
    }
    if (empty($req->items) || !is_array($req->items)) {
        return response()->json(['success' => false, 'message' => 'At least one purchase item is required'], 400);
    }

    DB::beginTransaction();
    try {
        // 1. Create purchase shell
        $purchase = Purchase::create([
            'supplier_id'    => $req->supplier_id,
            'warehouse_id'   => $req->warehouse_id,
            'reference'      => $req->reference ?? 'REF-' . time(),
            'purchase_no'    => $req->purchase_no    ?? 'PNO-' . time(),
            'invoice_number' => $req->invoice_number ?? 'INV-' . time(),
            'purchase_date'  => $req->purchase_date,
            'note'           => $req->note ?? '',
            'shipping'       => $req->shipping ?? 0,
            'order_tax'      => $req->order_tax ?? 0,
            'paid_amount'    => 0,      
            'status'         => $req->status ?? 'pending',
            'sub_total'      => 0,
            'total_amount'   => 0,
            'due_amount'     => 0,
            'payment_status' => 'due',
        ]);

        // 2. Create purchase items
        $subTotal = 0;
        foreach ($req->items as $row) {
            $purchase->items()->create([
                'product_id'  => $row['product_id']  ?? null,
                'quantity'    => $row['quantity']    ?? 1,
                'discount'    => $row['discount']    ?? 0,
                'tax_percent' => $row['tax_percent'] ?? 0,
                'tax_amount'  => $row['tax_amount']  ?? 0,
                'subtotal'    => $row['subtotal']    ?? 0,
            ]);
            $subTotal += $row['subtotal'] ?? 0;
        }

        // 3. Create payments (single payment)
        $paymentsTotal = 0;
        if ($req->filled('payment_amount') && $req->payment_amount > 0) {
            $purchase->payments()->create([
                'payment_date' => $req->payment_date ?? now(),
                'amount'       => $req->payment_amount,
                'method'       => $req->payment_method ?? 'cash',
             

            ]);
            $paymentsTotal += $req->payment_amount;
        }

        // 4. Create payments (multiple payments)
        if ($req->filled('payments') && is_array($req->payments)) {
            foreach ($req->payments as $pay) {
                if (!empty($pay['amount']) && $pay['amount'] > 0) {
                    $purchase->payments()->create([
                        'payment_date' => $pay['payment_date'] ?? now(),
                        'amount'       => $pay['amount'],
                        'method'       => $pay['method'] ?? 'cash',
                  

                    ]);
                    $paymentsTotal += $pay['amount'];
                }
            }
        }

        // 5. Update totals and status
        $purchase->paid_amount = $paymentsTotal;
        $this->syncTotals($purchase, $subTotal);

        DB::commit();

        return response()->json([
            'success'  => true,
            'purchase' => $purchase->fresh()->load(['items', 'payments']),
        ], 201);
    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


    // Show specific purchase with items and supplier
// Example in your PurchaseApiController.php

public function show($id)
{
    $purchase = Purchase::with(['supplier', 'items', 'payments'])->find($id);
    
    if (!$purchase) {
        return response()->json(['message' => 'Purchase not found'], 404);
    }

    return response()->json(['purchase' => $purchase]);
}


    // Update purchase and items
    public function update(Request $req, Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            $purchase->fill([
                'supplier_id'    => $req->supplier_id    ?? $purchase->supplier_id,
                'warehouse_id'   => $req->warehouse_id   ?? $purchase->warehouse_id,
                'reference'      => $req->reference      ?? $purchase->reference,
                'purchase_no'    => $req->purchase_no    ?? $purchase->purchase_no,
                'invoice_number' => $req->invoice_number ?? $purchase->invoice_number,
                'purchase_date'  => $req->purchase_date  ?? $purchase->purchase_date,
                'note'           => $req->note ?? $req->description ?? $purchase->note,
                'shipping'       => $req->shipping       ?? $purchase->shipping,
                'order_tax'      => $req->order_tax      ?? $purchase->order_tax,
                'paid_amount'    => $req->paid_amount    ?? $purchase->paid_amount,
                'status'         => $req->status         ?? $purchase->status,
            ])->save();

            if ($req->has('items')) {
                $purchase->items()->delete();

                $subTotal = 0;
                foreach ($req->items as $row) {
                    $purchase->items()->create([
                        'product_id'  => $row['product_id']  ?? null,
                        'unit_cost'   => $row['unit_cost']   ?? 0,   // NEW FIELD
                        'quantity'    => $row['quantity']    ?? 1,
                        'discount'    => $row['discount']    ?? 0,
                        'tax_percent' => $row['tax_percent'] ?? 0,
                        'tax_amount'  => $row['tax_amount']  ?? 0,
                        'subtotal'    => $row['subtotal']    ?? 0,
                    ]);
                    $subTotal += $row['subtotal'] ?? 0;
                }
            } else {
                $subTotal = $purchase->items()->sum('subtotal');
            }

            $this->syncTotals($purchase, $subTotal);
            DB::commit();

            return response()->json([
                'success'          => true,
                'updated_purchase' => $purchase->fresh()->load(['items.product', 'supplier', 'warehouse']),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            logger()->error($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete purchase and items
    public function destroy(Purchase $purchase)
    {
        $purchase->items()->delete();
        $purchase->delete();

        return response()->json(['success' => true, 'deleted_id' => $purchase->id]);
    }

    // Helper method to update totals and status
    private function syncTotals(Purchase $p, float $subTotal): void
    {
        $p->sub_total     = $subTotal;
        $p->total_amount  = $subTotal + $p->shipping + $p->order_tax;
        $p->due_amount    = max($p->total_amount - $p->paid_amount, 0);
        $p->payment_status = $p->due_amount == 0
            ? 'paid'
            : ($p->paid_amount == 0 ? 'due' : 'partial');
        $p->save();
    }
}
