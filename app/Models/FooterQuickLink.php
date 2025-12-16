<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterQuickLink extends Model
{
    protected $fillable = [
        'label',
        'url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
