<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\Product;
use App\Models\Warehouse\Warehouse;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $guarded = [];

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'type',
        'reference_id',
        'quantity_in',
        'quantity_out',
        'stock_date',
        'note',
    ];

    // ✅ Product relation
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // ✅ Warehouse relation (optional, if you want to show warehouse name)
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
