<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'slug','name','image','description','tags',
        'meta_title','meta_keywords','meta_description','status'
    ];

    protected $casts = ['tags' => 'array'];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id')
            ->where('status', '1')
            ->orderByRaw('COALESCE(`order_by`, 999999) ASC');
    }
}
