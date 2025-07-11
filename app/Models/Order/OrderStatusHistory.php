<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $table = 'order_status_histories';
    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    // Relationship to Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
