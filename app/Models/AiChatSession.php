<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatSession extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'model',
        'session_token',
        'ip_address',
        'user_agent',
        'is_active',
        'last_activity_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(AiChatMessage::class, 'session_id');
    }
}
