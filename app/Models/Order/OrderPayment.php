<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $table = 'order_payments';
  
    protected $fillable = [
        'order_id',
        'payment_date',
        'amount',
        'method',
        'note',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount'       => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
