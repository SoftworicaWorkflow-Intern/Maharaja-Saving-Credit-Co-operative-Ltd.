<?php

namespace App\Http\Requests;

use App\Models\AnnualReport;
use App\Models\FiscalYear;
use App\Models\Report;
use App\Models\ReportCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SaveReportRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'short_description_en' => $this->input('short_description_en', $this->input('short_description')),
            'description_en' => $this->input('description_en', $this->input('description')),
            'slug' => filled($this->input('slug'))
                ? $this->input('slug')
                : Str::slug((string) $this->input('title_en')),
        ]);
    }

    public function authorize(): bool
    {
        return $this->user()?->can(
            $this->isMethod('POST') ? 'create' : 'update',
            $this->isMethod('POST') ? Report::class : $this->route('report'),
        ) ?? false;
    }

    public function rules(): array
    {
        $report = $this->route('report');

        $categorySlug = ReportCategory::query()
            ->whereKey($this->input('category_id'))
            ->value('slug');
        $categoryMetadataRules = match ($categorySlug) {
            'financial-reports' => [
                'statement_type' => ['nullable', Rule::in(['balance-sheet', 'income-expenditure', 'profit-loss', 'cash-flow', 'changes-in-equity', 'other'])],
                'reporting_period' => ['nullable', 'string', 'max:100'],
            ],
            'audit-reports' => [
                'audit_type' => ['nullable', Rule::in(['external', 'internal', 'compliance'])],
                'auditor_name' => ['nullable', 'string', 'max:150'],
                'audit_period' => ['nullable', 'string', 'max:100'],
            ],
            'agm-reports' => [
                'meeting_date' => ['nullable', 'date'],
                'meeting_type' => ['nullable', Rule::in(['annual-general', 'special-general'])],
            ],
            'policies-guidelines' => [
                'policy_number' => ['nullable', 'string', 'max:100'],
                'version' => ['nullable', 'string', 'max:40'],
                'effective_date' => ['nullable', 'date'],
                'approval_date' => ['nullable', 'date'],
                'is_superseded' => ['sometimes', 'boolean'],
            ],
            'meeting-decisions' => [
                'meeting_type' => ['nullable', Rule::in(['board', 'general', 'agm', 'committee'])],
                'meeting_date' => ['nullable', 'date'],
            ],
            'other-publications' => [
                'publication_type' => ['nullable', Rule::in(['special-publication', 'institutional-information', 'research-study', 'awareness-material', 'community-development', 'official-circular', 'other'])],
            ],
            default => [],
        };

        $rules = [
            'title_en' => ['required', 'string', 'max:255'],
            'title_np' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash:ascii',
                Rule::unique('reports', 'slug')->ignore($report),
            ],
            'category_id' => ['required', 'integer', Rule::exists(ReportCategory::class, 'id')],
            'fiscal_year_id' => ['required', 'integer', Rule::exists(FiscalYear::class, 'id')],
            'publication_date' => ['required', 'date'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'published_by' => ['nullable', 'string', 'max:150'],
            'short_description_en' => ['required', 'string', 'max:500'],
            'short_description_np' => ['nullable', 'string', 'max:500'],
            'description_en' => ['nullable', 'string', 'max:20000'],
            'description_np' => ['nullable', 'string', 'max:20000'],
            'executive_summary_en' => ['nullable', 'string', 'max:20000'],
            'executive_summary_np' => ['nullable', 'string', 'max:20000'],
            'highlights_en' => ['nullable', 'string', 'max:20000'],
            'highlights_np' => ['nullable', 'string', 'max:20000'],
            'key_findings_en' => ['nullable', 'string', 'max:20000'],
            'key_findings_np' => ['nullable', 'string', 'max:20000'],
            'achievements_en' => ['nullable', 'string', 'max:20000'],
            'achievements_np' => ['nullable', 'string', 'max:20000'],
            'financial_summary_en' => ['nullable', 'string', 'max:20000'],
            'financial_summary_np' => ['nullable', 'string', 'max:20000'],
            'observations_en' => ['nullable', 'string', 'max:20000'],
            'observations_np' => ['nullable', 'string', 'max:20000'],
            'future_plans_en' => ['nullable', 'string', 'max:20000'],
            'future_plans_np' => ['nullable', 'string', 'max:20000'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'document' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'file',
                'mimes:pdf',
                'extensions:pdf',
                'max:20480',
            ],
            'document_visibility' => ['required', Rule::in(['private', 'public'])],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => [
                'file',
                'mimes:pdf,jpg,jpeg,png,webp',
                'extensions:pdf,jpg,jpeg,png,webp',
                'max:20480',
            ],
            'attachments_public' => ['sometimes', 'boolean'],
            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'extensions:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'language' => ['required', Rule::in(Report::LANGUAGES)],
            'status' => ['required', Rule::in(Report::STATUSES)],
            'featured' => ['sometimes', 'boolean'],
            'display_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'annual_chapters' => ['sometimes', 'array', 'max:20'],
            'annual_chapters.*' => ['array:chapter_key,title_en,title_np,content_en,content_np,image,remove_image,display_order,is_published'],
            'annual_chapters.*.chapter_key' => ['required', 'string', 'max:100', 'alpha_dash:ascii', 'distinct'],
            'annual_chapters.*.title_en' => ['required', 'string', 'max:255'],
            'annual_chapters.*.title_np' => ['nullable', 'string', 'max:255'],
            'annual_chapters.*.content_en' => ['nullable', 'string', 'max:50000'],
            'annual_chapters.*.content_np' => ['nullable', 'string', 'max:50000'],
            'annual_chapters.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'extensions:jpg,jpeg,png,webp', 'max:4096'],
            'annual_chapters.*.remove_image' => ['sometimes', 'boolean'],
            'annual_chapters.*.display_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'annual_chapters.*.is_published' => ['sometimes', 'boolean'],
            'annual_financials' => ['sometimes', 'array', 'max:10'],
            'annual_financials.*' => ['array:metric_key,value,comparison_value,is_published'],
            'annual_financials.*.metric_key' => ['required', 'string', 'distinct', Rule::in(array_keys(AnnualReport::FINANCIAL_METRICS))],
            'annual_financials.*.value' => ['nullable', 'numeric', 'min:0', 'max:999999999999999999.99'],
            'annual_financials.*.comparison_value' => ['nullable', 'numeric', 'min:0', 'max:999999999999999999.99'],
            'annual_financials.*.is_published' => ['sometimes', 'boolean'],
        ];

        if ($categoryMetadataRules !== []) {
            $rules['category_metadata'] = ['sometimes', 'array:'.implode(',', array_keys($categoryMetadataRules))];

            foreach ($categoryMetadataRules as $key => $fieldRules) {
                $rules["category_metadata.$key"] = $fieldRules;
            }
        }

        return $rules;
    }
}
