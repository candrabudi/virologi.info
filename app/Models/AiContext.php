<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiContext extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category',
        'use_internal_source',
        'is_active',
    ];

    protected $casts = [
        'use_internal_source' => 'boolean',
        'is_active' => 'boolean',
    ];
}
