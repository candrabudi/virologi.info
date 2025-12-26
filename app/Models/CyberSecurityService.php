<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CyberSecurityService extends Model
{
    use HasFactory;

    protected $table = 'cyber_security_services';

    protected $fillable = [
        'slug',
        'name',
        'short_name',
        'category',
        'summary',
        'description',
        'service_scope',
        'deliverables',
        'target_audience',
        'ai_keywords',
        'ai_domain',
        'is_ai_visible',
        'cta_label',
        'cta_url',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'service_scope' => 'array',
        'deliverables' => 'array',
        'target_audience' => 'array',
        'ai_keywords' => 'array',
        'seo_keywords' => 'array',
        'is_ai_visible' => 'boolean',
        'is_active' => 'boolean',
    ];
}
