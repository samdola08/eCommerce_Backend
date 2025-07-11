<?php

namespace App\Http\Controllers\API\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase\Purchase;
use App\Models\Stock\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PurchaseApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'purchases' => Purchase::with(['supplier', 'payments'])
                ->orderBy('purchase_date', 'desc')
                ->get()
        ]);
    }

    public function store(Request $req)
    {
        DB::beginTransaction();
        try {
            $purchase = Purchase::create([
                'supplier_id'    => $req->supplier_id,
                'warehouse_id'   => $req->warehouse_id,
                'reference'      => $req->reference,
                'purchase_no'    => $req->purchase_no    ?? 'PNO-' . uniqid(),
                'invoice_number' => $req->invoice_number ?? 'INV-' . uniqid(),
                'purchase_date'  => $req->purchase_date  ?? Carbon::today(),
                'note'           => $req->note ?? $req->description ?? '',
                'shipping'       => $req->shipping       ?? 0,
                'order_tax'      => $req->order_tax      ?? 0,
                'paid_amount'    => $req->paid_amount    ?? 0,
                'status'         => $req->status         ?? 'pending',
                'sub_total'      => 0,
                'total_amount'   => 0,
                'due_amount'     => 0,
                'payment_status' => 'due',
            ]);

            $subTotal = 0;
            foreach ($req->items ?? [] as $row) {
                $lineSubtotal = $this->lineSubtotal($row);

                $purchase->items()->create([
                    'product_id'  => $row['product_id'],
                    'unit_cost'   => $row['unit_cost']   ?? 0,
                    'quantity'    => $row['quantity']    ?? 1,
                    'discount'    => $row['discount']    ?? 0,
                    'tax_percent' => $row['tax_percent'] ?? 0,
                    'tax_amount'  => $row['tax_amount']  ?? 0,
                    'subtotal'    => $lineSubtotal,
                ]);

                // 🟢 Add stock entry
                Stock::create([
                    'product_id'    => $row['product_id'],
                    'warehouse_id'  => $req->warehouse_id,
                    'type'          => 'purchase',
                    'reference_id'  => $purchase->id,
                    'quantity_in'   => $row['quantity'] ?? 1,
                    'quantity_out'  => 0,
                    'stock_date'    => $req->purchase_date ?? now(),
                    'note'          => 'Purchase Entry',
                ]);

                $subTotal += $lineSubtotal;
            }

            $this->syncTotals($purchase, $subTotal);

            if ($purchase->paid_amount > 0) {
                $purchase->payments()->create([
                    'payment_date'  => now(),
                    'amount'        => $purchase->paid_amount,
                    'method'        => $req->payment_method ?? 'cash',
                    'reference_no'  => $req->payment_reference ?? 'REF-' . uniqid(),
                    'currency'      => $req->currency ?? 'BDT',
                    'exchange_rate' => $req->exchange_rate ?? 1,
                ]);
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'purchase' => $purchase->load(['items.product', 'payments']),
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            logger()->error('Purchase store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $purchase = Purchase::with([
            'supplier', 'warehouse', 'items.product', 'payments'
        ])->findOrFail($id);

        return response()->json([
            'success'  => true,
            'purchase' => $purchase,
        ]);
    }

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
            ])->save();

            $subTotal = 0;
            if ($req->has('items')) {
                $purchase->items()->delete();
                Stock::where('reference_id', $purchase->id)->where('type', 'purchase')->delete();

                foreach ($req->items as $row) {
                    $lineSubtotal = $this->lineSubtotal($row);

                    $purchase->items()->create([
                        'product_id'  => $row['product_id'],
                        'unit_cost'   => $row['unit_cost']   ?? 0,
                        'quantity'    => $row['quantity']    ?? 1,
                        'discount'    => $row['discount']    ?? 0,
                        'tax_percent' => $row['tax_percent'] ?? 0,
                        'tax_amount'  => $row['tax_amount']  ?? 0,
                        'subtotal'    => $lineSubtotal,
                    ]);

                    // 🔁 Update stock
                    Stock::create([
                        'product_id'    => $row['product_id'],
                        'warehouse_id'  => $purchase->warehouse_id,
                        'type'          => 'purchase',
                        'reference_id'  => $purchase->id,
                        'quantity_in'   => $row['quantity'] ?? 1,
                        'quantity_out'  => 0,
                        'stock_date'    => $purchase->purchase_date ?? now(),
                        'note'          => 'Updated Purchase Entry',
                    ]);

                    $subTotal += $lineSubtotal;
                }
            } else {
                $subTotal = $purchase->items()->sum('subtotal');
            }

            if ($req->has('payments')) {
                $purchase->payments()->delete();

                foreach ($req->payments as $p) {
                    $purchase->payments()->create([
                        'payment_date'  => $p['payment_date'] ?? now(),
                        'amount'        => $p['amount'] ?? 0,
                        'method'        => $p['method'] ?? 'cash',
                        'reference_no'  => $p['reference_no'] ?? 'REF-' . uniqid(),
                        'currency'      => $req->currency ?? 'BDT',
                        'exchange_rate' => $req->exchange_rate ?? 1,
                    ]);
                }

                $purchase->paid_amount = $purchase->payments()->sum('amount');
                $purchase->save();
            }

            $this->syncTotals($purchase, $subTotal);

            DB::commit();

            return response()->json([
                'success'          => true,
                'updated_purchase' => $purchase->fresh()->load(['items.product', 'supplier', 'warehouse', 'payments']),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            logger()->error('Purchase update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->items()->delete();
        $purchase->payments()->delete();

        // ❌ Remove stock entries
        Stock::where('reference_id', $purchase->id)->where('type', 'purchase')->delete();

        $purchase->delete();

        return response()->json([
            'success'    => true,
            'deleted_id' => $purchase->id,
        ]);
    }

    private function syncTotals(Purchase $p, float $subTotal): void
    {
        $p->sub_total      = $subTotal;
        $p->total_amount   = $subTotal + $p->shipping + $p->order_tax;
        $p->due_amount     = max($p->total_amount - $p->paid_amount, 0);
        $p->payment_status = $p->due_amount == 0
            ? 'paid'
            : ($p->paid_amount == 0 ? 'due' : 'partial');
        $p->save();
    }

    private function lineSubtotal(array $row): float
    {
        $unit      = $row['unit_cost']   ?? 0;
        $qty       = $row['quantity']    ?? 1;
        $discount  = $row['discount']    ?? 0;
        $taxAmount = $row['tax_amount']  ?? 0;

        return ($unit * $qty) - $discount + $taxAmount;
    }
}
