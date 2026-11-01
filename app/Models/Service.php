<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'category',
        'title_en',
        'title_np',
        'short_description_en',
        'short_description_np',
        'full_description_en',
        'full_description_np',
        'icon',
        'benefits_en',
        'benefits_np',
        'eligibility_en',
        'eligibility_np',
        'required_documents_en',
        'required_documents_np',
        'process_en',
        'process_np',
        'purpose_en',
        'purpose_np',
        'is_active',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'benefits_en' => 'array',
            'benefits_np' => 'array',
            'required_documents_en' => 'array',
            'required_documents_np' => 'array',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSavings($query)
    {
        return $query->where('category', 'savings');
    }

    public function scopeLoans($query)
    {
        return $query->where('category', 'loan');
    }

    public function scopeMemberSupport($query)
    {
        return $query->where('category', 'member_support');
    }

    public function title(): string
    {
        $locale = app()->getLocale();
        return ($locale === 'ne' && !empty($this->title_np)) ? $this->title_np : $this->title_en;
    }

    public function shortDescription(): string
    {
        $locale = app()->getLocale();
        return ($locale === 'ne' && !empty($this->short_description_np)) ? $this->short_description_np : $this->short_description_en;
    }

    public function fullDescription(): string
    {
        $locale = app()->getLocale();
        return ($locale === 'ne' && !empty($this->full_description_np)) ? $this->full_description_np : ($this->full_description_en ?? $this->shortDescription());
    }

    public function benefits(): array
    {
        $locale = app()->getLocale();
        $benefits = ($locale === 'ne' && !empty($this->benefits_np)) ? $this->benefits_np : ($this->benefits_en ?? []);
        return is_array($benefits) ? $benefits : [];
    }

    public function requiredDocuments(): array
    {
        $locale = app()->getLocale();
        $docs = ($locale === 'ne' && !empty($this->required_documents_np)) ? $this->required_documents_np : ($this->required_documents_en ?? []);
        return is_array($docs) ? $docs : [];
    }

    public function eligibility(): ?string
    {
        $locale = app()->getLocale();
        return ($locale === 'ne' && !empty($this->eligibility_np)) ? $this->eligibility_np : $this->eligibility_en;
    }

    public function purpose(): ?string
    {
        $locale = app()->getLocale();
        return ($locale === 'ne' && !empty($this->purpose_np)) ? $this->purpose_np : $this->purpose_en;
    }

    public function process(): ?string
    {
        $locale = app()->getLocale();
        return ($locale === 'ne' && !empty($this->process_np)) ? $this->process_np : $this->process_en;
    }
}

