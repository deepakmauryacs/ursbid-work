<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'cat_id',
        'sub_id',
        'super_id',
        'title',
        'description',
        'image',
        'user_type',
        'insert_by',
        'update_by',
        'slug',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'status',
        'order_by',
        'post_date',
    ];
}
