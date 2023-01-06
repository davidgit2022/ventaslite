<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDatails extends Model
{
    use HasFactory;

    protected $fillable = [
        'prince',
        'quantity',
        'product_id',
        'sale_id'
    ];
}
