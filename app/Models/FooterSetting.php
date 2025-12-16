<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    protected $fillable = [
        'logo_path',
        'description',
        'copyright_text',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
