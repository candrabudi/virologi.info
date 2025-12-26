<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiResourceRoute extends Model
{
    protected $table = 'ai_resource_routes';

    protected $fillable = [
        'scope_code',
        'resource_type',
        'resource_id',
        'keyword',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'resource_id' => 'integer',
        'weight' => 'integer',
        'is_active' => 'boolean',
    ];
}
