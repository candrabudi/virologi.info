<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'slug',

        'name',
        'subtitle',
        'summary',
        'content',

        'product_type',

        'ai_domain',
        'ai_level',
        'ai_keywords',
        'ai_intents',
        'ai_use_cases',
        'ai_priority',
        'is_ai_visible',
        'is_ai_recommended',

        'cta_label',
        'cta_url',
        'cta_type',

        'thumbnail',

        'seo_title',
        'seo_description',
        'seo_keywords',
        'canonical_url',

        'ai_view_count',
        'ai_click_count',

        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'ai_keywords' => 'array',
        'ai_intents' => 'array',
        'ai_use_cases' => 'array',
        'seo_keywords' => 'array',

        'is_ai_visible' => 'boolean',
        'is_ai_recommended' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_primary', true);
    }
}
