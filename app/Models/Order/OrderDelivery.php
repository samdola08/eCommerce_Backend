<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order\OrderDeliveryItem;
use App\Models\WareHouse\Warehouse;
use App\Models\Order\Order;

class OrderDelivery extends Model
{
    protected $table ='order_deliveries';
    
    protected $fillable = [
        'order_id',
        'warehouse_id',
        'delivery_person',
        'delivery_company',
        'delivery_note',
        'delivery_data',
        'delivery_status',
    ];

    protected $casts = [
        'delivery_data' => 'datetime',
    ];
        public function items()
    {
        return $this->hasMany(OrderDeliveryItem::class, 'delivery_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function order()
{
    return $this->belongsTo(\App\Models\Order\Order::class);
}

}
