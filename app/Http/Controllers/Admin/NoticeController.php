<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveNoticeRequest;
use App\Models\Notice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

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
        $notice->update($this->noticeAttributes($request));

        return redirect()
            ->route('admin.notices.edit', $notice)
            ->with('status', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return redirect()
            ->route('admin.notices.index')
            ->with('status', 'Notice deleted successfully.');
    }

    private function noticeAttributes(SaveNoticeRequest $request): array
    {
        $attributes = $request->validated();
        $attributes['featured'] = $request->boolean('featured');

        if ($attributes['status'] === 'published' && empty($attributes['published_at'])) {
            $attributes['published_at'] = now();
        }

        return $attributes;
    }
}
