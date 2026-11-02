<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalYear extends Model
{
    protected $fillable = [
        'label',
        'label_en',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function localizedLabel(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $locale === 'ne' ? $this->label : ($this->label_en ?: $this->label);
    }
}
