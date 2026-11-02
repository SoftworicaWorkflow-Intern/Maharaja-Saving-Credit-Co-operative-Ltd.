<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportStatistic extends Model
{
    protected $fillable = [
        'fiscal_year_id',
        'label_en',
        'label_np',
        'value',
        'unit_en',
        'unit_np',
        'display_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'display_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereHas('fiscalYear', fn (Builder $query) => $query->where('is_active', true));
    }

    public function localizedLabel(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ne'
            ? ($this->label_np ?: $this->label_en)
            : $this->label_en;
    }

    public function localizedUnit(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ne' ? ($this->unit_np ?: $this->unit_en) : $this->unit_en;
    }
}
