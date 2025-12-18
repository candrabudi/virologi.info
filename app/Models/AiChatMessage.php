<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatMessage extends Model
{
    protected $fillable = [
        'session_id',
        'role',
        'content',
        'response_engine',
        'is_liked',
        'tokens',
        'latency_ms',
    ];

    protected $casts = [
        'is_liked' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(AiChatSession::class, 'session_id');
    }
}
