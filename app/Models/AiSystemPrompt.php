<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSystemPrompt extends Model
{
    protected $table = 'ai_system_prompts';

    protected $fillable = [
        'scope_code',
        'code',
        'intent_code',
        'behavior',
        'resource_type',
        'content',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'priority' => 'integer',
        'is_active' => 'boolean',
    ];
}
