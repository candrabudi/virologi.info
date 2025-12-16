<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPromptBinding extends Model
{
    protected $fillable = [
        'ai_context_id',
        'ai_prompt_template_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function context()
    {
        return $this->belongsTo(AiContext::class, 'ai_context_id');
    }

    public function prompt()
    {
        return $this->belongsTo(AiPromptTemplate::class, 'ai_prompt_template_id');
    }
}
