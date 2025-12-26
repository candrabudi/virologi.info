<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiDecisionLog extends Model
{
    protected $fillable = [
        'user_id', 'session_id',
        'raw_prompt', 'normalized_prompt',
        'intent_code', 'decision', 'feedback',
    ];
}
