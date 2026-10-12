<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    public const CATEGORIES = ['general', 'savings', 'loan'];

    public const STATUSES = ['draft', 'published'];

    protected $fillable = [
        'title_np',
        'title_en',
        'body_np',
        'body_en',
        'category',
        'status',
        'published_at',
        'featured',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
    ];

    public function scopeActivePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
