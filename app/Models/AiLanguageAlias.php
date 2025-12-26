<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLanguageAlias extends Model
{
    protected $fillable = [
        'raw_term', 'normalized_term',
        'language_code', 'target_language',
        'confidence', 'used_count', 'is_active',
    ];

    protected $casts = [
        'confidence' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
    ];
}
