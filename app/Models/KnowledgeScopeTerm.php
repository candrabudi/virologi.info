<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeScopeTerm extends Model
{
    protected $fillable = ['knowledge_scope_id', 'term', 'category', 'weight'];
    protected $casts = ['weight' => 'integer'];
}
