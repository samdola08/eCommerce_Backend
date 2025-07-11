<?php

namespace App\Models\Inventory;


use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier\Supplier;
class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
          'name', 'brand_id', 'category_id', 'supplier_id',
        'barcode', 'price', 'discount', 'tax', 'quantity',
        'status', 'img', 'description'
    ];

    /* ─────── relations ─────── */
    public function brand()    { return $this->belongsTo(Brand::class);               }
    public function category() { return $this->belongsTo(Category::class);            }
    public function supplier() { return $this->belongsTo(Supplier::class);            }
}

