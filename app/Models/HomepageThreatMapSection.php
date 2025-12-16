<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageThreatMapSection extends Model
{
    protected $fillable = [
        'pre_title',
        'title',
        'description',
        'cta_text',
        'cta_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
