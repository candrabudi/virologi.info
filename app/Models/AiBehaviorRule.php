<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiBehaviorRule extends Model
{
    protected $fillable = [
        'intent_code', 'pattern', 'decision',
        'priority', 'success_count', 'fail_count', 'is_active',
    ];

    protected $casts = [
        'priority' => 'integer',
        'success_count' => 'integer',
        'fail_count' => 'integer',
        'is_active' => 'boolean',
    ];
}
