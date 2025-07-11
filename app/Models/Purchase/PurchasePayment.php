<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    protected $table    = 'purchase_payments';

    protected $fillable = [
        'purchase_id','payment_date','amount','method','reference_no',
        'currency','exchange_rate'
    ];

    /* Relationships */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}