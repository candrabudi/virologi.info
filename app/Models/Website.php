<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'name',
        'tagline',
        'description',
        'long_description',
        'phone_number',
        'email',
        'logo_rectangle',
        'logo_square',
        'favicon',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'og_url',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_card',
        'canonical_url',
        'robots_meta',
        'extra_meta',
    ];

    protected $casts = [
        'extra_meta' => 'array',
    ];
}
