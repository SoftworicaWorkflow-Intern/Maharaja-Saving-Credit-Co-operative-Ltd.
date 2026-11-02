@php
    $annualChapterValues = old('annual_chapters');
    if ($annualChapterValues === null) {
        $existingChapters = $report->annualReport?->chapters ?? collect();
        $annualChapterValues = $existingChapters->isNotEmpty()
            ? $existingChapters->map(fn ($chapter) => $chapter->only(['chapter_key', 'title_en', 'title_np', 'content_en', 'content_np', 'image_path', 'display_order', 'is_published']))->all()
            : collect(\App\Models\AnnualReport::CHAPTERS)->map(fn ($chapter, $key) => [
                'chapter_key' => $key,
                ...$chapter,
                'content_en' => '',
                'content_np' => '',
                'display_order' => array_search($key, array_keys(\App\Models\AnnualReport::CHAPTERS), true) + 1,
                'is_published' => false,
            ])->all();
    }

    $annualFinancialValues = old('annual_financials');
    if ($annualFinancialValues === null) {
        $existingFinancials = ($report->annualReport?->financialStatistics ?? collect())->keyBy('metric_key');
        $annualFinancialValues = collect(\App\Models\AnnualReport::FINANCIAL_METRICS)->map(function ($metric, $key) use ($existingFinancials) {
            $financial = $existingFinancials->get($key);

            return [
                'metric_key' => $key,
                'value' => $financial?->value,
                'comparison_value' => $financial?->comparison_value,
                'is_published' => $financial?->is_published ?? false,
            ];
        })->all();
    }

    $annualReportSelected = (string) old('category_id', $report->category_id) === (string) $annualCategoryId;
    $categoryMetadata = old('category_metadata', $report->category_metadata ?? []);
    $selectedCategoryId = (string) old('category_id', $report->category_id);
    $selectedCategorySlug = $categories->first(fn ($category) => (string) $category->id === $selectedCategoryId)?->slug;
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="max-w-5xl space-y-6 rounded-xl border border-[#DCE9DF] bg-white p-5 shadow-sm sm:p-8">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <fieldset class="space-y-5">
        <legend class="text-lg font-bold text-[#14532D]">{{ __('General information') }}</legend>
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="title_en" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Report Title (English)') }}</label>
            <input id="title_en" name="title_en" value="{{ old('title_en', $report->title_en) }}" required maxlength="255" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#27804A]/20">
            @error('title_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="title_np" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Report Title (Nepali)') }}</label>
            <input id="title_np" name="title_np" lang="ne" value="{{ old('title_np', $report->title_np) }}" required maxlength="255" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#27804A]/20">
            @error('title_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="slug" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Report slug') }}</label>
            <input id="slug" name="slug" value="{{ old('slug', $report->slug) }}" maxlength="255" pattern="[A-Za-z0-9_-]+" autocomplete="off" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#27804A]/20">
            <p class="mt-1 text-xs text-[#617067]">{{ __('Leave blank to generate a URL-friendly slug from the English title.') }}</p>
            @error('slug') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="category_id" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Report Category') }}</label>
            <select id="category_id" name="category_id" data-report-category-selector data-annual-category-id="{{ $annualCategoryId }}" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22]">
                <option value="">{{ __('Select a category') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" data-category-slug="{{ $category->slug }}" @selected((string) old('category_id', $report->category_id) === (string) $category->id)>{{ $category->name_en }}{{ $category->is_active ? '' : ' (Inactive)' }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="fiscal_year_id" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Fiscal Year') }}</label>
            <select id="fiscal_year_id" name="fiscal_year_id" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22]">
                <option value="">{{ __('Select a fiscal year') }}</option>
                @foreach ($fiscalYears as $fiscalYear)
                    <option value="{{ $fiscalYear->id }}" @selected((string) old('fiscal_year_id', $report->fiscal_year_id) === (string) $fiscalYear->id)>{{ $fiscalYear->label }} / {{ $fiscalYear->label_en }}{{ $fiscalYear->is_active ? '' : ' (Inactive)' }}</option>
                @endforeach
            </select>
            @error('fiscal_year_id') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="publication_date" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Publication Date') }}</label>
            <input id="publication_date" type="date" name="publication_date" value="{{ old('publication_date', $report->publication_date?->format('Y-m-d')) }}" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22]">
            @error('publication_date') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="language" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Language') }}</label>
            <select id="language" name="language" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22]">
                <option value="en" @selected(old('language', $report->language ?: 'both') === 'en')>{{ __('English') }}</option>
                <option value="ne" @selected(old('language', $report->language ?: 'both') === 'ne')>{{ __('Nepali') }}</option>
                <option value="both" @selected(old('language', $report->language ?: 'both') === 'both')>{{ __('English & Nepali') }}</option>
            </select>
            @error('language') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="display_order" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Display Order') }}</label>
            <input id="display_order" type="number" min="0" max="65535" name="display_order" value="{{ old('display_order', $report->display_order ?? 0) }}" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22]">
            @error('display_order') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="status" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Publication Status') }}</label>
            <select id="status" name="status" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22]">
                <option value="draft" @selected(old('status', $report->status ?: 'draft') === 'draft')>{{ __('Draft (not public)') }}</option>
                <option value="published" @selected(old('status', $report->status ?: 'draft') === 'published')>{{ __('Published') }}</option>
            </select>
            @error('status') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="reference_number" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Report Reference Number') }}</label>
            <input id="reference_number" name="reference_number" maxlength="100" value="{{ old('reference_number', $report->reference_number) }}" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22]">
            @error('reference_number') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="published_by" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Published By') }}</label>
            <input id="published_by" name="published_by" maxlength="150" value="{{ old('published_by', $report->published_by) }}" placeholder="{{ __('For example, Board of Directors') }}" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22]">
            @error('published_by') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2">
            <label for="short_description_en" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Short Description (English)') }}</label>
            <textarea id="short_description_en" name="short_description_en" rows="3" maxlength="500" required class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22]">{{ old('short_description_en', $report->short_description) }}</textarea>
            @error('short_description_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2">
            <label for="short_description_np" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Short Description (Nepali)') }}</label>
            <textarea id="short_description_np" name="short_description_np" lang="ne" rows="3" maxlength="500" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22]">{{ old('short_description_np', $report->short_description_np) }}</textarea>
            @error('short_description_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>
    </fieldset>

    <fieldset data-report-category-fields data-category-slug="financial-reports" @disabled($selectedCategorySlug !== 'financial-reports') class="{{ $selectedCategorySlug === 'financial-reports' ? 'space-y-5 border-t border-[#E8EFEA] pt-5' : 'hidden' }}">
        <legend class="text-lg font-bold text-[#14532D]">{{ __('Financial statement details') }}</legend>
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="financial-statement-type" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Statement type') }}</label>
                <select id="financial-statement-type" name="category_metadata[statement_type]" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22]">
                    <option value="">{{ __('Select statement type') }}</option>
                    @foreach (['balance-sheet' => 'Balance Sheet', 'income-expenditure' => 'Income and Expenditure', 'profit-loss' => 'Profit and Loss', 'cash-flow' => 'Cash Flow', 'changes-in-equity' => 'Changes in Equity', 'other' => 'Other statement'] as $value => $label)
                        <option value="{{ $value }}" @selected(($categoryMetadata['statement_type'] ?? '') === $value)>{{ __($label) }}</option>
                    @endforeach
                </select>
                @error('category_metadata.statement_type') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="financial-reporting-period" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Reporting period') }}</label>
                <input id="financial-reporting-period" name="category_metadata[reporting_period]" maxlength="100" value="{{ $categoryMetadata['reporting_period'] ?? '' }}" placeholder="{{ __('Optional statement period') }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                @error('category_metadata.reporting_period') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>
    </fieldset>

    <fieldset data-report-category-fields data-category-slug="audit-reports" @disabled($selectedCategorySlug !== 'audit-reports') class="{{ $selectedCategorySlug === 'audit-reports' ? 'space-y-5 border-t border-[#E8EFEA] pt-5' : 'hidden' }}">
        <legend class="text-lg font-bold text-[#14532D]">{{ __('Audit information') }}</legend>
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="audit-type" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Audit type') }}</label>
                <select id="audit-type" name="category_metadata[audit_type]" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                    <option value="">{{ __('Select audit type') }}</option>
                    @foreach (['external' => 'External', 'internal' => 'Internal', 'compliance' => 'Compliance review'] as $value => $label)
                        <option value="{{ $value }}" @selected(($categoryMetadata['audit_type'] ?? '') === $value)>{{ __($label) }}</option>
                    @endforeach
                </select>
                @error('category_metadata.audit_type') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="audit-period" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Audit period') }}</label>
                <input id="audit-period" name="category_metadata[audit_period]" maxlength="100" value="{{ $categoryMetadata['audit_period'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                @error('category_metadata.audit_period') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label for="auditor-name" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Auditor or auditing organization (approved for publication)') }}</label>
                <input id="auditor-name" name="category_metadata[auditor_name]" maxlength="150" value="{{ $categoryMetadata['auditor_name'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                @error('category_metadata.auditor_name') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>
    </fieldset>

    <fieldset data-report-category-fields data-category-slug="agm-reports" @disabled($selectedCategorySlug !== 'agm-reports') class="{{ $selectedCategorySlug === 'agm-reports' ? 'space-y-5 border-t border-[#E8EFEA] pt-5' : 'hidden' }}">
        <legend class="text-lg font-bold text-[#14532D]">{{ __('Annual General Meeting details') }}</legend>
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="agm-meeting-date" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Meeting date') }}</label>
                <input id="agm-meeting-date" type="date" name="category_metadata[meeting_date]" value="{{ $categoryMetadata['meeting_date'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                @error('category_metadata.meeting_date') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="agm-meeting-type" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Meeting type') }}</label>
                <select id="agm-meeting-type" name="category_metadata[meeting_type]" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                    <option value="">{{ __('Select meeting type') }}</option>
                    <option value="annual-general" @selected(($categoryMetadata['meeting_type'] ?? '') === 'annual-general')>{{ __('Annual General Meeting') }}</option>
                    <option value="special-general" @selected(($categoryMetadata['meeting_type'] ?? '') === 'special-general')>{{ __('Special General Meeting') }}</option>
                </select>
                @error('category_metadata.meeting_type') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>
    </fieldset>

    <fieldset data-report-category-fields data-category-slug="policies-guidelines" @disabled($selectedCategorySlug !== 'policies-guidelines') class="{{ $selectedCategorySlug === 'policies-guidelines' ? 'space-y-5 border-t border-[#E8EFEA] pt-5' : 'hidden' }}">
        <legend class="text-lg font-bold text-[#14532D]">{{ __('Policy version and approval') }}</legend>
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="policy-number" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Policy number') }}</label>
                <input id="policy-number" name="category_metadata[policy_number]" maxlength="100" value="{{ $categoryMetadata['policy_number'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                @error('category_metadata.policy_number') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="policy-version" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Version') }}</label>
                <input id="policy-version" name="category_metadata[version]" maxlength="40" value="{{ $categoryMetadata['version'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                @error('category_metadata.version') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="policy-effective-date" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Effective date') }}</label>
                <input id="policy-effective-date" type="date" name="category_metadata[effective_date]" value="{{ $categoryMetadata['effective_date'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                @error('category_metadata.effective_date') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="policy-approval-date" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Approval date') }}</label>
                <input id="policy-approval-date" type="date" name="category_metadata[approval_date]" value="{{ $categoryMetadata['approval_date'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                @error('category_metadata.approval_date') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <label class="inline-flex items-center gap-2 text-sm font-semibold text-[#344054]">
                <input type="hidden" name="category_metadata[is_superseded]" value="0">
                <input type="checkbox" name="category_metadata[is_superseded]" value="1" @checked((bool) ($categoryMetadata['is_superseded'] ?? false)) class="rounded border-[#B6C9BC] text-[#14532D]">
                {{ __('This policy version is superseded') }}
            </label>
        </div>
    </fieldset>

    <fieldset data-report-category-fields data-category-slug="meeting-decisions" @disabled($selectedCategorySlug !== 'meeting-decisions') class="{{ $selectedCategorySlug === 'meeting-decisions' ? 'space-y-5 border-t border-[#E8EFEA] pt-5' : 'hidden' }}">
        <legend class="text-lg font-bold text-[#14532D]">{{ __('Meeting decision details') }}</legend>
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="decision-meeting-date" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Meeting date') }}</label>
                <input id="decision-meeting-date" type="date" name="category_metadata[meeting_date]" value="{{ $categoryMetadata['meeting_date'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                @error('category_metadata.meeting_date') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="decision-meeting-type" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Meeting type') }}</label>
                <select id="decision-meeting-type" name="category_metadata[meeting_type]" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                    <option value="">{{ __('Select meeting type') }}</option>
                    @foreach (['board' => 'Board', 'general' => 'General meeting', 'agm' => 'AGM', 'committee' => 'Committee'] as $value => $label)
                        <option value="{{ $value }}" @selected(($categoryMetadata['meeting_type'] ?? '') === $value)>{{ __($label) }}</option>
                    @endforeach
                </select>
                @error('category_metadata.meeting_type') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>
    </fieldset>

    <fieldset data-report-category-fields data-category-slug="other-publications" @disabled($selectedCategorySlug !== 'other-publications') class="{{ $selectedCategorySlug === 'other-publications' ? 'space-y-5 border-t border-[#E8EFEA] pt-5' : 'hidden' }}">
        <legend class="text-lg font-bold text-[#14532D]">{{ __('Publication details') }}</legend>
        <div>
            <label for="publication-type" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Publication type') }}</label>
            <select id="publication-type" name="category_metadata[publication_type]" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm sm:max-w-xl">
                <option value="">{{ __('Select publication type') }}</option>
                @foreach (['special-publication' => 'Special publication', 'institutional-information' => 'Institutional information', 'research-study' => 'Research or study', 'awareness-material' => 'Member awareness material', 'community-development' => 'Community development', 'official-circular' => 'Official circular', 'other' => 'Other'] as $value => $label)
                    <option value="{{ $value }}" @selected(($categoryMetadata['publication_type'] ?? '') === $value)>{{ __($label) }}</option>
                @endforeach
            </select>
            @error('category_metadata.publication_type') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </fieldset>

    <fieldset data-annual-report-fields @disabled(! $annualReportSelected) class="{{ $annualReportSelected ? 'space-y-5 border-t border-[#E8EFEA] pt-5' : 'hidden' }}">
        <legend class="text-lg font-bold text-[#14532D]">{{ __('Annual report chapters') }}</legend>
        <p class="text-sm leading-6 text-[#617067]">{{ __('Enter the approved English and Nepali chapter content. Chapters appear publicly only when the report and the individual chapter are published.') }}</p>
        <div data-annual-chapter-list data-next-chapter-index="{{ count($annualChapterValues) }}" class="space-y-4">
            @foreach ($annualChapterValues as $index => $chapter)
                <details data-annual-chapter-row @if ($index < 2) open @endif class="rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] p-4">
                    <summary class="cursor-pointer font-semibold text-[#14532D]">{{ $chapter['title_en'] ?? __('Annual report chapter') }}</summary>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <input type="hidden" name="annual_chapters[{{ $index }}][chapter_key]" value="{{ $chapter['chapter_key'] }}">
                        <div>
                            <label for="annual-chapter-title-en-{{ $index }}" class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Chapter title (English)') }}</label>
                            <input id="annual-chapter-title-en-{{ $index }}" name="annual_chapters[{{ $index }}][title_en]" value="{{ $chapter['title_en'] }}" required maxlength="255" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                            @error("annual_chapters.$index.title_en") <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="annual-chapter-title-np-{{ $index }}" class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Chapter title (Nepali)') }}</label>
                            <input id="annual-chapter-title-np-{{ $index }}" name="annual_chapters[{{ $index }}][title_np]" value="{{ $chapter['title_np'] ?? '' }}" lang="ne" maxlength="255" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                        </div>
                        <div>
                            <label for="annual-chapter-content-en-{{ $index }}" class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Chapter content (English)') }}</label>
                            <textarea id="annual-chapter-content-en-{{ $index }}" name="annual_chapters[{{ $index }}][content_en]" rows="6" maxlength="50000" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">{{ $chapter['content_en'] ?? '' }}</textarea>
                            @error("annual_chapters.$index.content_en") <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="annual-chapter-content-np-{{ $index }}" class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Chapter content (Nepali)') }}</label>
                            <textarea id="annual-chapter-content-np-{{ $index }}" name="annual_chapters[{{ $index }}][content_np]" lang="ne" rows="6" maxlength="50000" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">{{ $chapter['content_np'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label for="annual-chapter-order-{{ $index }}" class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Display order') }}</label>
                            <input id="annual-chapter-order-{{ $index }}" type="number" min="0" max="65535" name="annual_chapters[{{ $index }}][display_order]" value="{{ $chapter['display_order'] ?? $index + 1 }}" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                        </div>
                        <div>
                            <label for="annual-chapter-image-{{ $index }}" class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Optional chapter image') }}</label>
                            <input id="annual-chapter-image-{{ $index }}" type="file" name="annual_chapters[{{ $index }}][image]" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-[#DCE9DF] bg-white px-3 py-2 text-sm">
                            @if (! empty($chapter['image_path']))
                                <label class="mt-2 inline-flex items-center gap-2 text-xs text-[#617067]">
                                    <input type="checkbox" name="annual_chapters[{{ $index }}][remove_image]" value="1" class="rounded border-[#B6C9BC] text-[#14532D]">
                                    {{ __('Remove current chapter image') }}
                                </label>
                            @endif
                            @error("annual_chapters.$index.image") <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex flex-wrap items-end justify-between gap-3">
                            <label class="inline-flex items-center gap-2 text-sm font-semibold text-[#344054]">
                                <input type="checkbox" name="annual_chapters[{{ $index }}][is_published]" value="1" @checked($chapter['is_published'] ?? false) class="rounded border-[#B6C9BC] text-[#14532D]">
                                {{ __('Approve and publish this chapter') }}
                            </label>
                            <button type="button" data-remove-annual-chapter class="text-sm font-semibold text-red-700 underline">{{ __('Remove chapter') }}</button>
                        </div>
                    </div>
                </details>
            @endforeach
        </div>
        <button type="button" data-add-annual-chapter class="rounded-xl border border-[#14532D] px-4 py-2.5 text-sm font-semibold text-[#14532D] hover:bg-[#E7F4EB]">{{ __('Add chapter') }}</button>
        @error('annual_chapters') <p class="text-sm text-red-700">{{ $message }}</p> @enderror

        <template data-annual-chapter-template>
            <details data-annual-chapter-row open class="rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] p-4">
                <summary class="cursor-pointer font-semibold text-[#14532D]">{{ __('New annual report chapter') }}</summary>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <input type="hidden" data-chapter-key>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Chapter title (English)') }}</label>
                        <input data-chapter-field="title_en" required maxlength="255" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Chapter title (Nepali)') }}</label>
                        <input data-chapter-field="title_np" lang="ne" maxlength="255" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Chapter content (English)') }}</label>
                        <textarea data-chapter-field="content_en" rows="6" maxlength="50000" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Chapter content (Nepali)') }}</label>
                        <textarea data-chapter-field="content_np" lang="ne" rows="6" maxlength="50000" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Display order') }}</label>
                        <input data-chapter-field="display_order" type="number" min="0" max="65535" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-[#182A22]">{{ __('Optional chapter image') }}</label>
                        <input data-chapter-field="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-[#DCE9DF] bg-white px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-end justify-between gap-3">
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-[#344054]">
                            <input data-chapter-field="is_published" type="checkbox" value="1" class="rounded border-[#B6C9BC] text-[#14532D]">
                            {{ __('Approve and publish this chapter') }}
                        </label>
                        <button type="button" data-remove-annual-chapter class="text-sm font-semibold text-red-700 underline">{{ __('Remove chapter') }}</button>
                    </div>
                </div>
            </details>
        </template>

        <fieldset class="space-y-4 rounded-2xl border border-[#DCE9DF] p-4 sm:p-5">
            <legend class="px-2 text-base font-bold text-[#14532D]">{{ __('Verified financial information') }}</legend>
            <p class="text-sm leading-6 text-[#617067]">{{ __('Enter figures only after verification against approved institutional records. Unapproved values are never shown publicly.') }}</p>
            @foreach ($annualFinancialValues as $index => $financial)
                @php($metric = \App\Models\AnnualReport::FINANCIAL_METRICS[$financial['metric_key']])
                <div class="grid gap-3 border-t border-[#E8EFEA] pt-4 sm:grid-cols-2 lg:grid-cols-[minmax(12rem,1fr)_1fr_1fr_auto] lg:items-end">
                    <input type="hidden" name="annual_financials[{{ $index }}][metric_key]" value="{{ $financial['metric_key'] }}">
                    <p class="text-sm font-semibold text-[#182A22]">{{ app()->getLocale() === 'ne' ? $metric['label_np'] : $metric['label_en'] }} <span class="font-normal text-[#617067]">({{ app()->getLocale() === 'ne' ? $metric['unit_np'] : $metric['unit_en'] }})</span></p>
                    <div>
                        <label for="annual-financial-value-{{ $index }}" class="mb-1 block text-xs font-medium text-[#617067]">{{ __('Verified current value') }}</label>
                        <input id="annual-financial-value-{{ $index }}" type="number" name="annual_financials[{{ $index }}][value]" min="0" step="0.01" value="{{ $financial['value'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-3 py-2.5 text-sm">
                        @error("annual_financials.$index.value") <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="annual-financial-previous-{{ $index }}" class="mb-1 block text-xs font-medium text-[#617067]">{{ __('Prior-year comparison (optional)') }}</label>
                        <input id="annual-financial-previous-{{ $index }}" type="number" name="annual_financials[{{ $index }}][comparison_value]" min="0" step="0.01" value="{{ $financial['comparison_value'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-3 py-2.5 text-sm">
                    </div>
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-[#344054]">
                        <input type="checkbox" name="annual_financials[{{ $index }}][is_published]" value="1" @checked($financial['is_published'] ?? false) class="rounded border-[#B6C9BC] text-[#14532D]">
                        {{ __('Publish') }}
                    </label>
                </div>
            @endforeach
        </fieldset>
    </fieldset>

    <fieldset class="space-y-5 border-t border-[#E8EFEA] pt-5">
        <legend class="text-lg font-bold text-[#14532D]">{{ __('Report content') }}</legend>
    <div class="grid gap-5 sm:grid-cols-2">
        @foreach ([
            ['description', 'Full description'],
            ['executive_summary', 'Executive summary'],
            ['highlights', 'Report highlights'],
            ['key_findings', 'Key findings'],
            ['achievements', 'Institutional achievements'],
            ['financial_summary', 'Financial summary'],
            ['observations', 'Important observations'],
            ['future_plans', 'Future plans'],
        ] as [$field, $label])
            <div class="sm:col-span-2 grid gap-4 rounded-xl border border-[#E8EFEA] p-4 sm:grid-cols-2">
                <div>
                    <label for="{{ $field }}_en" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __($label) }} ({{ __('English') }})</label>
                    <textarea id="{{ $field }}_en" name="{{ $field }}_en" rows="4" maxlength="20000" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22]">{{ old($field.'_en', $field === 'description' ? $report->description : $report->{$field.'_en'}) }}</textarea>
                    @error($field.'_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="{{ $field }}_np" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __($label) }} ({{ __('Nepali') }})</label>
                    <textarea id="{{ $field }}_np" name="{{ $field }}_np" lang="ne" rows="4" maxlength="20000" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22]">{{ old($field.'_np', $field === 'description' ? $report->description_np : $report->{$field.'_np'}) }}</textarea>
                    @error($field.'_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
            </div>
        @endforeach
    </div>
    </fieldset>

    <fieldset class="space-y-5 border-t border-[#E8EFEA] pt-5">
        <legend class="text-lg font-bold text-[#14532D]">{{ __('Document information') }}</legend>
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="document" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Upload PDF File') }}</label>
            <input id="document" type="file" name="document" accept=".pdf,application/pdf" @required($method === 'POST') class="block w-full rounded-xl border border-[#DCE9DF] bg-white px-3 py-2.5 text-sm text-[#182A22] file:mr-3 file:rounded-lg file:border-0 file:bg-[#E7F4EB] file:px-3 file:py-2 file:text-sm file:font-semibold file:text-[#14532D]">
            <p class="mt-1 text-xs text-[#617067]">{{ __('PDF only. Maximum file size: 20 MB.') }} @if ($report->exists) {{ __('Leave empty to keep the current document.') }} @endif</p>
            @if ($report->exists)
                <p class="mt-1 text-xs text-[#617067]">{{ __('Current document: :size', ['size' => $report->formattedFileSize()]) }}</p>
            @endif
            @error('document') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="document_visibility" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('File Visibility') }}</label>
            <select id="document_visibility" name="document_visibility" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22]">
                <option value="private" @selected(old('document_visibility', $report->document_visibility ?: 'private') === 'private')>{{ __('Private — administrator only') }}</option>
                <option value="public" @selected(old('document_visibility', $report->document_visibility ?: 'private') === 'public')>{{ __('Public — approved for visitors') }}</option>
            </select>
            <p class="mt-1 text-xs text-[#617067]">{{ __('Only publish documents approved for public access. Private documents remain in secure storage.') }}</p>
            @error('document_visibility') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="featured_image" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Featured Image (optional)') }}</label>
            <input id="featured_image" type="file" name="featured_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-[#DCE9DF] bg-white px-3 py-2.5 text-sm text-[#182A22] file:mr-3 file:rounded-lg file:border-0 file:bg-[#E7F4EB] file:px-3 file:py-2 file:text-sm file:font-semibold file:text-[#14532D]">
            <p class="mt-1 text-xs text-[#617067]">{{ __('JPG, PNG, or WebP. Maximum file size: 4 MB.') }}</p>
            @error('featured_image') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2">
            <label for="attachments" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('Additional public attachments (optional)') }}</label>
            <input id="attachments" type="file" name="attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp,application/pdf,image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-[#DCE9DF] bg-white px-3 py-2.5 text-sm text-[#182A22] file:mr-3 file:rounded-lg file:border-0 file:bg-[#E7F4EB] file:px-3 file:py-2 file:text-sm file:font-semibold file:text-[#14532D]">
            <p class="mt-1 text-xs text-[#617067]">{{ __('Up to 5 files per save. PDF or JPG/PNG/WebP, 20 MB maximum each. Attachments are private unless explicitly approved below.') }}</p>
            @error('attachments') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            @error('attachments.*') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            <label class="mt-3 flex items-start gap-3 rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] p-4 text-sm text-[#344054]">
                <input type="checkbox" name="attachments_public" value="1" @checked(old('attachments_public')) class="mt-0.5 rounded border-[#B6C9BC] text-[#14532D]">
                <span><span class="block font-semibold text-[#182A22]">{{ __('Approve uploaded attachments for public access') }}</span><span class="mt-1 block text-xs text-[#617067]">{{ __('Only select this after confirming every uploaded file is safe and intended for publication.') }}</span></span>
            </label>
        </div>
        @if ($report->exists && $report->attachments->isNotEmpty())
            <div class="sm:col-span-2">
                <h3 class="text-sm font-semibold text-[#182A22]">{{ __('Existing attachments') }}</h3>
                <ul class="mt-2 space-y-2">
                    @foreach ($report->attachments as $attachment)
                        <li class="flex flex-wrap justify-between gap-2 text-sm text-[#617067]">
                            <span>{{ $attachment->original_name }}</span>
                            <span>{{ $attachment->is_public ? __('Public') : __('Private') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    </fieldset>

    <fieldset class="space-y-5 border-t border-[#E8EFEA] pt-5">
        <legend class="text-lg font-bold text-[#14532D]">{{ __('Search engine information') }}</legend>
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="seo_title" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('SEO Title') }}</label>
                <input id="seo_title" name="seo_title" maxlength="255" value="{{ old('seo_title', $report->seo_title) }}" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22]">
                @error('seo_title') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="seo_description" class="mb-2 block text-sm font-semibold text-[#182A22]">{{ __('SEO Description') }}</label>
                <textarea id="seo_description" name="seo_description" rows="2" maxlength="500" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22]">{{ old('seo_description', $report->seo_description) }}</textarea>
                @error('seo_description') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>
    </fieldset>

    <div>
        <label class="flex items-start gap-3 rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] p-4 text-sm text-[#344054] sm:col-span-2">
            <input type="checkbox" name="featured" value="1" @checked(old('featured', $report->featured)) class="mt-0.5 rounded border-[#B6C9BC] text-[#14532D] focus:ring-[#27804A]">
            <span><span class="block font-semibold text-[#182A22]">{{ __('Featured Report') }}</span><span class="mt-1 block text-xs text-[#617067]">{{ __('Highlight this publication in the public reports section.') }}</span></span>
        </label>
    </div>

    <div class="flex flex-wrap items-center gap-3 border-t border-[#E8EFEA] pt-5">
        <button type="submit" class="rounded-xl bg-[#14532D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#27804A]">{{ $method === 'POST' ? ($annualReportSelected ? __('Create Annual Report') : __('Create Report')) : __('Save Changes') }}</button>
        <a href="{{ $annualReportSelected ? route('admin.annual-reports.index') : route('admin.reports.index') }}" class="rounded-xl border border-[#DCE9DF] px-5 py-2.5 text-sm font-semibold text-[#344054] hover:bg-[#F5F8F5]">{{ __('Cancel') }}</a>
    </div>
</form>
