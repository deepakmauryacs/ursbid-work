<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    protected $fillable = [
        'category_id','slug','name','image','description','tags',
        'meta_title','meta_keywords','meta_description','status','order_by'
    ];

    protected $casts = ['tags' => 'array'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
