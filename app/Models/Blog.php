<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $fillable = [
        'title',
        'post_date',
        'description',
        'image',
        'slug',
        'order_by',
        'status',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];
    public $timestamps = false;
}
