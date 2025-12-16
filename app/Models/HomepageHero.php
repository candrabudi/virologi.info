<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageHero extends Model
{
    protected $fillable = [
        'pre_title',
        'pre_icon',
        'title',
        'subtitle',
        'video_path',
        'overlay_color',
        'overlay_opacity',
        'primary_button_text',
        'primary_button_url',
        'secondary_button_text',
        'secondary_button_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'overlay_opacity' => 'float',
    ];
}
