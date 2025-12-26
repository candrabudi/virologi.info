<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiLanguageFeedback extends Model
{
    protected $fillable = [
        'user_id', 'raw_term',
        'suggested_term', 'language_code', 'status',
    ];
}
