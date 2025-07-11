<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers'; 
    protected $fillable = [
    'name', 'phone', 'email', 'address', 'company_name'
];
}
