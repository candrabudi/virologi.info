<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $table = 'about_us';

    protected $fillable = [
        'breadcrumb_pre',
        'breadcrumb_bg',
        'page_title',
        'headline',
        'left_content',
        'right_content',
        'topics',
        'manifesto',
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
        'topics' => 'array',
        'manifesto' => 'array',
        'is_active' => 'boolean',
    ];
}
