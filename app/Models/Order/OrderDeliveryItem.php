<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Product;


class OrderDeliveryItem extends Model
{
    protected $table = 'order_delivery_items';
        protected $fillable = ['delivery_id', 'product_id', 'quantity'];

    public function delivery()
    {
        return $this->belongsTo(OrderDelivery::class, 'delivery_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
