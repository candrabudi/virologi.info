<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLanguageStat extends Model
{
    protected $fillable = ['language_code', 'total_messages', 'learning_count'];
    protected $casts = [
        'total_messages' => 'integer',
        'learning_count' => 'integer',
    ];
}
