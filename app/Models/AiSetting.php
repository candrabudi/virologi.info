<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    protected $table = 'ai_settings';

    protected $fillable = [
        'provider',
        'base_url',
        'api_key',
        'model',
        'temperature',
        'max_tokens',
        'timeout',
        'is_active',
        'cybersecurity_only',
    ];

    protected $casts = [
        'temperature' => 'float',
        'max_tokens' => 'integer',
        'timeout' => 'integer',
        'is_active' => 'boolean',
        'cybersecurity_only' => 'boolean',
    ];
}
