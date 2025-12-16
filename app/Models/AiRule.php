<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiRule extends Model
{
    protected $fillable = [
        'type',
        'value',
        'ai_context_id',
        'category',
        'is_active',
        'note',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function context()
    {
        return $this->belongsTo(AiContext::class, 'ai_context_id');
    }
}
