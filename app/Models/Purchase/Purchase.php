<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier\Supplier;
use App\Models\WareHouse\Warehouse;


class Purchase extends Model
{
    protected $table    = 'purchases';
    protected $guarded  = ['id'];
    protected $fillable = [
        'supplier_id',
        'warehouse_id',
        'reference',
        'purchase_no',
        'invoice_number',
        'purchase_date',
        'note',
        'shipping',
        'order_tax',
        'sub_total',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_status',
        'status'
    ];

    protected $casts = [

        'shipping'   => 'float',
        'order_tax'  => 'float',
        'sub_total'  => 'float',
        'total_amount' => 'float',
        'paid_amount' => 'float',
        'due_amount' => 'float',
    ];


    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function payments()
{
    return $this->hasMany(PurchasePayment::class, 'purchase_id');
}

}
