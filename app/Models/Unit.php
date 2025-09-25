<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'unit';

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'title',
        'slug',
        'status',
    ];
}
