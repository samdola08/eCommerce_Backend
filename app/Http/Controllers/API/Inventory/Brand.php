<?php

namespace App\Models\Inventory;
use App\Models\Inventory\Category;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'product_brands'; 
    // Brand.php
public function category()
{
    return $this->belongsTo(Category::class);
}

}
