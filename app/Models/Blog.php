<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $fillable = [
        'title',
        'description',
        'image',
        'slug',
        'status',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'custom_header_code',
    ];
    public $timestamps = false;
}
