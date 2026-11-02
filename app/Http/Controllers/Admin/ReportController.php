<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveReportRequest;
use App\Models\FiscalYear;
use App\Models\Report;
use App\Models\ReportCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(Report::STATUSES)],
            'category' => ['nullable', 'integer', 'exists:report_categories,id'],
            'fiscal_year' => ['nullable', 'integer', 'exists:fiscal_years,id'],
        ]);

        $query = Report::query()->with(['category', 'fiscalYear']);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (! empty($filters['fiscal_year'])) {
            $query->where('fiscal_year_id', $filters['fiscal_year']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search): void {
                $query->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_np', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $reports = $query
            ->orderByRaw("CASE WHEN status = 'draft' THEN 0 ELSE 1 END")
            ->orderBy('display_order')
            ->orderByDesc('publication_date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.index', [
            'reports' => $reports,
            'filters' => $filters,
            'categories' => ReportCategory::query()->orderBy('display_order')->orderBy('name_en')->get(),
            'fiscalYears' => FiscalYear::query()->orderByDesc('display_order')->orderByDesc('id')->get(),
            'stats' => [
                'total' => Report::query()->count(),
                'published' => Report::query()->where('status', 'published')->count(),
                'drafts' => Report::query()->where('status', 'draft')->count(),
                'featured' => Report::query()->where('featured', true)->count(),
                'categories' => ReportCategory::query()->count(),
                'views' => (int) Report::query()->sum('views'),
                'document_views' => (int) Report::query()->sum('document_views'),
                'downloads' => (int) Report::query()->sum('downloads'),
            ],
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Report::class);

        $report = new Report;
        $report->category_id = request()->query('category_id');
        $report->setRelation('annualReport', null);

        return view('admin.reports.create', [
            'report' => $report,
            'categories' => ReportCategory::query()->orderBy('display_order')->orderBy('name_en')->get(),
            'fiscalYears' => FiscalYear::query()->orderByDesc('display_order')->orderByDesc('id')->get(),
            'annualCategoryId' => ReportCategory::query()->where('slug', 'annual-reports')->value('id'),
        ]);
    }

    public function store(SaveReportRequest $request): RedirectResponse
    {
        $attributes = $this->reportAttributes($request);
        $attributes['featured'] = $request->boolean('featured');
        $document = $request->file('document');
        $attributes['document_path'] = $this->storeFile($document, 'reports');
        $attributes['document_size'] = $document->getSize();

        if ($request->hasFile('featured_image')) {
            $attributes['featured_image_path'] = $this->storeFile($request->file('featured_image'), 'report-images');
        }

        $report = Report::query()->create($attributes);
        $this->storeAttachments($request, $report);
        $this->syncAnnualContent($request, $report);

        return redirect()
            ->route('admin.reports.edit', $report)
            ->with('status', 'Report created successfully.');
    }

    public function edit(Report $report): View
    {
        Gate::authorize('update', $report);
        $report->load(['category', 'annualReport.chapters', 'annualReport.financialStatistics', 'attachments']);

        return view('admin.reports.edit', [
            'report' => $report,
            'categories' => ReportCategory::query()->orderBy('display_order')->orderBy('name_en')->get(),
            'fiscalYears' => FiscalYear::query()->orderByDesc('display_order')->orderByDesc('id')->get(),
            'annualCategoryId' => ReportCategory::query()->where('slug', 'annual-reports')->value('id'),
        ]);
    }

    public function update(SaveReportRequest $request, Report $report): RedirectResponse
    {
        Gate::authorize('update', $report);

        $attributes = $this->reportAttributes($request);
        $attributes['featured'] = $request->boolean('featured');
        $oldDocumentPath = $report->document_path;
        $oldImagePath = $report->featured_image_path;

        if ($request->hasFile('document')) {
            $document = $request->file('document');
            $attributes['document_path'] = $this->storeFile($document, 'reports');
            $attributes['document_size'] = $document->getSize();
        }

        if ($request->hasFile('featured_image')) {
            $attributes['featured_image_path'] = $this->storeFile($request->file('featured_image'), 'report-images');
        }

        $report->update($attributes);
        $this->storeAttachments($request, $report);
        $this->syncAnnualContent($request, $report);

        if (isset($attributes['document_path']) && $oldDocumentPath !== $attributes['document_path']) {
            $this->deleteFile($oldDocumentPath);
        }

        if (isset($attributes['featured_image_path']) && $oldImagePath !== $attributes['featured_image_path']) {
            $this->deleteFile($oldImagePath);
        }

        return redirect()
            ->route('admin.reports.edit', $report)
            ->with('status', 'Report updated successfully.');
    }

    public function destroy(Report $report): RedirectResponse
    {
        Gate::authorize('delete', $report);

        $documentPath = $report->document_path;
        $imagePath = $report->featured_image_path;
        $attachmentPaths = $report->attachments()->pluck('file_path');
        $chapterImagePaths = $report->annualReport()
            ->with('chapters:id,annual_report_id,image_path')
            ->get()
            ->pluck('chapters')
            ->flatten()
            ->pluck('image_path')
            ->filter();
        $report->delete();

        $this->deleteFile($documentPath);
        $this->deleteFile($imagePath);
        foreach ($attachmentPaths->merge($chapterImagePaths) as $filePath) {
            $this->deleteFile($filePath);
        }

        return redirect()
            ->route('admin.reports.index')
            ->with('status', 'Report deleted successfully.');
    }

    private function storeFile(UploadedFile $file, string $directory): string
    {
        $path = $file->store($directory, 'local');

        if (! is_string($path)) {
            throw new RuntimeException('The report file could not be stored.');
        }

        return $path;
    }

    private function reportAttributes(SaveReportRequest $request): array
    {
        $attributes = $request->safe()->except([
            'document',
            'featured_image',
            'featured',
            'attachments',
            'attachments_public',
            'annual_chapters',
            'annual_financials',
            'short_description_en',
            'description_en',
        ]);
        if (array_key_exists('category_metadata', $attributes)) {
            $metadata = array_filter(
                $attributes['category_metadata'] ?? [],
                fn ($value) => $value !== null && $value !== '',
            );
            $attributes['category_metadata'] = $metadata === [] ? null : $metadata;
        }
        $attributes['slug'] = $request->validated('slug');
        $attributes['short_description'] = $request->validated('short_description_en');
        $attributes['description'] = $request->validated('description_en');

        return $attributes;
    }

    private function syncAnnualContent(SaveReportRequest $request, Report $report): void
    {
        if (! $report->category()->where('slug', 'annual-reports')->exists()) {
            return;
        }

        $annualReport = $report->annualReport()->firstOrCreate([]);

        if ($request->has('annual_chapters')) {
            $chapters = $request->validated('annual_chapters', []);
            $submittedChapterKeys = array_column($chapters, 'chapter_key');
            $removedChapters = $annualReport->chapters()
                ->whereNotIn('chapter_key', $submittedChapterKeys)
                ->get();

            $annualReport->chapters()
                ->whereNotIn('chapter_key', $submittedChapterKeys)
                ->delete();

            foreach ($removedChapters as $removedChapter) {
                $this->deleteFile($removedChapter->image_path);
            }

            foreach ($chapters as $index => $chapter) {
                $existingChapter = $annualReport->chapters()
                    ->where('chapter_key', $chapter['chapter_key'])
                    ->first();
                $attributes = [
                    'title_en' => $chapter['title_en'],
                    'title_np' => $chapter['title_np'] ?? null,
                    'content_en' => $chapter['content_en'] ?? null,
                    'content_np' => $chapter['content_np'] ?? null,
                    'display_order' => $chapter['display_order'],
                    'is_published' => (bool) ($chapter['is_published'] ?? false),
                ];
                $oldImagePath = $existingChapter?->image_path;

                if ($request->hasFile("annual_chapters.$index.image")) {
                    $attributes['image_path'] = $this->storeFile(
                        $request->file("annual_chapters.$index.image"),
                        'annual-report-chapters',
                    );
                } elseif ($request->boolean("annual_chapters.$index.remove_image")) {
                    $attributes['image_path'] = null;
                }

                $annualReport->chapters()->updateOrCreate(
                    ['chapter_key' => $chapter['chapter_key']],
                    $attributes,
                );

                if ($oldImagePath !== null && isset($attributes['image_path']) && $oldImagePath !== $attributes['image_path']) {
                    $this->deleteFile($oldImagePath);
                }
            }
        }

        if ($request->has('annual_financials')) {
            $financials = $request->validated('annual_financials', []);
            $submittedMetricKeys = array_column($financials, 'metric_key');
            $annualReport->financialStatistics()
                ->whereNotIn('metric_key', $submittedMetricKeys)
                ->delete();

            foreach ($financials as $financial) {
                if (! filled($financial['value'] ?? null)) {
                    $annualReport->financialStatistics()
                        ->where('metric_key', $financial['metric_key'])
                        ->delete();

                    continue;
                }

                $annualReport->financialStatistics()->updateOrCreate(
                    ['metric_key' => $financial['metric_key']],
                    [
                        'value' => $financial['value'],
                        'comparison_value' => $financial['comparison_value'] ?? null,
                        'is_published' => (bool) ($financial['is_published'] ?? false),
                    ],
                );
            }
        }
    }

    private function storeAttachments(SaveReportRequest $request, Report $report): void
    {
        foreach ($request->file('attachments', []) as $file) {
            $path = $this->storeFile($file, 'report-attachments');

            $report->attachments()->create([
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
                'file_size' => $file->getSize(),
                'is_public' => $request->boolean('attachments_public'),
            ]);
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('local')->exists($path) && ! Storage::disk('local')->delete($path)) {
            throw new RuntimeException('A report file could not be removed from private storage.');
        }
    }
}
