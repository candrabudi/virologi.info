<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiUsageLog extends Model
{
    protected $fillable = [
        'provider',
        'model',
        'category',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'ip_address',
        'user_agent',
        'is_blocked',
        'block_reason',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
    ];
}
