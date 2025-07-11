<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderShipment extends Model
{
       protected $table ='order_shipments';
          protected $fillable = [
        'order_id',
        'shipment_date',
        'tracking_number',
        'carrier',
        'status',
        'notes',
    ];

    protected $casts = [
        'shipment_date' => 'datetime',
    ];

    // Relationship to Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
}
