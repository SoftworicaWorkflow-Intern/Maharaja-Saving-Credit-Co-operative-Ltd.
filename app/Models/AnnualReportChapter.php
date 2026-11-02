<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnualReportChapter extends Model
{
    protected $fillable = [
        'chapter_key',
        'title_en',
        'title_np',
        'content_en',
        'content_np',
        'image_path',
        'display_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function annualReport(): BelongsTo
    {
        return $this->belongsTo(AnnualReport::class);
    }

    public function localizedTitle(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ne'
            ? ($this->title_np ?: $this->title_en)
            : ($this->title_en ?: $this->title_np);
    }

    public function localizedContent(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ne'
            ? ($this->content_np ?: $this->content_en)
            : ($this->content_en ?: $this->content_np);
    }
}
