<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Product;


class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'tax',
        'subtotal',
    ];

     public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
