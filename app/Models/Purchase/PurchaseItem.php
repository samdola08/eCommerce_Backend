<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Product;


class PurchaseItem extends Model
{


    protected $table = 'purchase_items';
    protected $fillable = [
              'purchase_id',
        'product_id',
        'unit_cost',    
        'quantity',
        'discount',
        'tax_percent',
        'tax_amount',
        'subtotal',
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'discount'   => 'float',
        'tax_percent'=> 'float',
        'tax_amount' => 'float',
        'subtotal'   => 'float',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
      public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
}
