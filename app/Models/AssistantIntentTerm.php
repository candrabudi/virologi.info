<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistantIntentTerm extends Model
{
    protected $fillable = ['assistant_intent_id', 'term', 'weight'];
    protected $casts = ['weight' => 'integer'];
}
