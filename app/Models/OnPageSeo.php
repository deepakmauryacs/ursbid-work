<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnPageSeo extends Model
{
    protected $table = 'on_page_seo';

    protected $fillable = [
        'page_url',
        'page_name',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];
}

