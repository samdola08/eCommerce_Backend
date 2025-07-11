<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer\Customer;



class Order extends Model
{
    
    protected $table = 'orders';     

    protected $fillable = [
        'customer_id',
        'order_no',
        'order_date',
        'delivery_date',
        'status',
        'payment_status',
        'total_amount',
        'paid_amount',
        'due_amount',
        'shipping_address',
        'discount_amount',
        'vat_amount',
        'note',
    ];

    protected $casts = [
        'order_date'    => 'datetime',
        'delivery_date' => 'datetime',
        'total_amount'  => 'decimal:2',
        'paid_amount'   => 'decimal:2',
        'due_amount'    => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'vat_amount'      => 'decimal:2',
    ];

    /* ------------ relationships ------------ */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(OrderPayment::class, 'order_id');
    }

  // Inside App\Models\Order.php

public function statuses()
{
    return $this->hasMany(OrderStatusHistory::class, 'order_id');
}


}
