<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterContact extends Model
{
    protected $fillable = [
        'type',
        'label',
        'value',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
