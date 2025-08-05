<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    protected $table = 'product_brands';

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'product_id',
        'brand_name',
        'description',
        'slug',
        'status',
    ];
}
