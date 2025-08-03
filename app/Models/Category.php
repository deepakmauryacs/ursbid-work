<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $fillable = [
        'title',
        'post_date',
        'image',
        'slug',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'status',
    ];
    public $timestamps = false;
}
