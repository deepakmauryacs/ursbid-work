<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_description',
        'site_keywords',
        'site_logo_1',
        'site_logo_2',
        'site_favicon',
        'copyright_text',
        'custom_code_header',
        'custom_code_footer',
    ];
}
