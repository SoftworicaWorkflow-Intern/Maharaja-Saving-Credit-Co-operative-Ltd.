<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'question_en',
        'question_np',
        'answer_en',
        'answer_np',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function question(): string
    {
        $locale = app()->getLocale();
        return ($locale === 'ne' && !empty($this->question_np)) ? $this->question_np : $this->question_en;
    }

    public function answer(): string
    {
        $locale = app()->getLocale();
        return ($locale === 'ne' && !empty($this->answer_np)) ? $this->answer_np : $this->answer_en;
    }
}

