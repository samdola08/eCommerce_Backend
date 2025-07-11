<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

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
}
