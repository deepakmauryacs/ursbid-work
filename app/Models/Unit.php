<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'unit';

    protected $fillable = [
        'cat_id',
        'sub_id',
        'title',
        'slug',
        'status',
    ];
}
