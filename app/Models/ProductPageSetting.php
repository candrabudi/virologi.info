<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPageSetting extends Model
{
    protected $fillable = [
        'page_title',
        'page_subtitle',
        'background_video',
        'cta_text',
        'cta_url',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
