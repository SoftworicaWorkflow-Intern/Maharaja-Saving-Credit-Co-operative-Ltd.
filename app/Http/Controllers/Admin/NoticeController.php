<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveNoticeRequest;
use App\Models\Notice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class NoticeController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(Notice::STATUSES)],
        ]);

        $query = Notice::query();

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search): void {
                $query->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_np', 'like', "%{$search}%");
            });
        }

        $notices = $query
            ->orderByRaw("CASE WHEN status = 'draft' THEN 0 ELSE 1 END")
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.notices.index', compact('notices', 'filters'));
    }

    public function create(): View
    {
        return view('admin.notices.create', [
            'notice' => new Notice,
        ]);
    }

    public function store(SaveNoticeRequest $request): RedirectResponse
    {
        $notice = Notice::query()->create($this->noticeAttributes($request));

        return redirect()
            ->route('admin.notices.edit', $notice)
            ->with('status', 'Notice created successfully.');
    }

    public function edit(Notice $notice): View
    {
        return view('admin.notices.edit', compact('notice'));
    }

    public function update(SaveNoticeRequest $request, Notice $notice): RedirectResponse
    {
        $previousDocumentPath = $notice->document_path;
        $notice->update($this->noticeAttributes($request));
        $this->deleteReplacedDocument($previousDocumentPath, $notice->document_path);

        return redirect()
            ->route('admin.notices.edit', $notice)
            ->with('status', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $documentPath = $notice->document_path;
        $notice->delete();
        $this->deleteReplacedDocument($documentPath, null);

        return redirect()
            ->route('admin.notices.index')
            ->with('status', 'Notice deleted successfully.');
    }

    private function noticeAttributes(SaveNoticeRequest $request): array
    {
        $attributes = $request->safe()->except(['document', 'remove_document']);
        $attributes['featured'] = $request->boolean('featured');

        if ($attributes['status'] === 'published' && empty($attributes['published_at'])) {
            $attributes['published_at'] = now();
        }

        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('notices', 'local');

            if (! is_string($documentPath)) {
                throw new RuntimeException('The notice document could not be stored.');
            }

            $attributes['document_path'] = $documentPath;
            $attributes['document_mime_type'] = $request->file('document')->getMimeType();
        } elseif ($request->boolean('remove_document')) {
            $attributes['document_path'] = null;
            $attributes['document_mime_type'] = null;
        }

        return $attributes;
    }

    private function deleteReplacedDocument(?string $previousPath, ?string $currentPath): void
    {
        if ($previousPath !== null && $previousPath !== $currentPath) {
            Storage::disk('local')->delete($previousPath);
        }
    }
}
