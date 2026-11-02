<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Report extends Model
{
    public const STATUSES = ['draft', 'published'];

    public const LANGUAGES = ['en', 'ne', 'both'];

    protected $fillable = [
        'title_en',
        'title_np',
        'slug',
        'category_id',
        'fiscal_year_id',
        'publication_date',
        'short_description',
        'short_description_np',
        'description',
        'description_np',
        'reference_number',
        'published_by',
        'category_metadata',
        'executive_summary_en',
        'executive_summary_np',
        'highlights_en',
        'highlights_np',
        'key_findings_en',
        'key_findings_np',
        'achievements_en',
        'achievements_np',
        'financial_summary_en',
        'financial_summary_np',
        'observations_en',
        'observations_np',
        'future_plans_en',
        'future_plans_np',
        'seo_title',
        'seo_description',
        'document_path',
        'document_size',
        'document_visibility',
        'featured_image_path',
        'language',
        'status',
        'featured',
        'display_order',
        'downloads',
    ];

    protected function casts(): array
    {
        return [
            'publication_date' => 'date',
            'category_metadata' => 'array',
            'document_size' => 'integer',
            'featured' => 'boolean',
            'display_order' => 'integer',
            'downloads' => 'integer',
            'views' => 'integer',
            'document_views' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ReportCategory::class, 'category_id');
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ReportAttachment::class);
    }

    public function annualReport(): HasOne
    {
        return $this->hasOne(AnnualReport::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->whereDate('publication_date', '<=', today());
    }

    public function localizedTitle(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ne'
            ? ($this->title_np ?: $this->title_en)
            : ($this->title_en ?: $this->title_np);
    }

    public function localizedShortDescription(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ne'
            ? ($this->short_description_np ?: $this->short_description)
            : ($this->short_description ?: $this->short_description_np);
    }

    public function localizedDescription(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ne'
            ? ($this->description_np ?: $this->description)
            : ($this->description ?: $this->description_np);
    }

    public function localizedContent(string $field, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();
        $localizedField = $field.'_'.($locale === 'ne' ? 'np' : 'en');

        return $this->{$localizedField} ?: $this->{$field.'_'.($locale === 'ne' ? 'en' : 'np')};
    }

    public function formattedFileSize(): string
    {
        if ($this->document_size >= 1_048_576) {
            return number_format($this->document_size / 1_048_576, 1).' MB';
        }

        return number_format(max(1, (int) ceil($this->document_size / 1024))).' KB';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
