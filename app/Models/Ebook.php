<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ebook extends Model
{
    protected $table = 'ebooks';

    protected $fillable = [
        'uuid',
        'slug',
        'title',
        'subtitle',
        'summary',
        'content',
        'level',
        'topic',
        'chapters',
        'learning_objectives',
        'ai_keywords',
        'cover_image',
        'file_path',
        'file_type',
        'page_count',
        'author',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'chapters' => 'array',
        'learning_objectives' => 'array',
        'ai_keywords' => 'array',
        'meta_keywords' => 'array',
        'published_at' => 'date',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (!$model->uuid) {
                $model->uuid = (string) Str::uuid();
            }

            if (!$model->slug && $model->title) {
                $base = Str::slug($model->title);
                $slug = $base;
                $i = 2;

                while (self::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$i++;
                }

                $model->slug = $slug;
            }
        });

        static::updating(function (self $model) {
            if (!$model->slug && $model->title) {
                $base = Str::slug($model->title);
                $slug = $base;
                $i = 2;

                while (
                    self::where('slug', $slug)
                        ->where('id', '!=', $model->id)
                        ->exists()
                ) {
                    $slug = $base.'-'.$i++;
                }

                $model->slug = $slug;
            }
        });
    }
}
