<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnualReportFinancialStatistic extends Model
{
    protected $fillable = [
        'metric_key',
        'value',
        'comparison_value',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'comparison_value' => 'decimal:2',
            'is_published' => 'boolean',
        ];
    }

    public function annualReport(): BelongsTo
    {
        return $this->belongsTo(AnnualReport::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereHas('annualReport', fn (Builder $query) => $query->published());
    }

    public function label(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return AnnualReport::FINANCIAL_METRICS[$this->metric_key]['label_'.($locale === 'ne' ? 'np' : 'en')]
            ?? $this->metric_key;
    }

    public function unit(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return AnnualReport::FINANCIAL_METRICS[$this->metric_key]['unit_'.($locale === 'ne' ? 'np' : 'en')]
            ?? '';
    }

    public function formattedValue(?string $value = null): string
    {
        $precision = AnnualReport::FINANCIAL_METRICS[$this->metric_key]['decimal_places'] ?? 2;

        return number_format((float) ($value ?? $this->value), $precision);
    }
}
