<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Member;
use App\Models\Notice;
use App\Models\Report;
use App\Models\ReportStatistic;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FrontendController extends Controller
{
    public function home(): View
    {
        $settings = Schema::hasTable('settings') ? Setting::values() : [];
        $leadershipMessages = config('leadership.messages');

        foreach ($leadershipMessages as $leader => $message) {
            $leadershipMessages[$leader]['name'] = $settings[$message['name_setting']] ?? null;
            $portraitPath = $settings[$message['portrait_setting']] ?? null;
            $leadershipMessages[$leader]['has_portrait'] = is_string($portraitPath)
                && Storage::disk('local')->exists($portraitPath);
        }

        $tickerNotices = Schema::hasTable('notices')
            ? Notice::query()
                ->activePublished()
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(10)
                ->get()
            : collect();

        $featuredNotices = $tickerNotices->take(7);
        $latestReports = Schema::hasTable('reports')
            ? Report::query()
                ->published()
                ->where('document_visibility', 'public')
                ->whereHas('category', fn ($query) => $query->where('is_active', true))
                ->whereHas('fiscalYear', fn ($query) => $query->where('is_active', true))
                ->with(['category', 'fiscalYear'])
                ->orderByDesc('publication_date')
                ->orderByDesc('id')
                ->limit(4)
                ->get()
            : collect();
        $latestAnnualReport = Schema::hasTable('annual_reports')
            ? Report::query()
                ->published()
                ->whereHas('category', fn ($query) => $query
                    ->where('slug', 'annual-reports')
                    ->where('is_active', true))
                ->whereHas('fiscalYear', fn ($query) => $query->where('is_active', true))
                ->whereHas('annualReport')
                ->with(['category', 'fiscalYear'])
                ->orderByDesc('publication_date')
                ->orderByDesc('id')
                ->first()
            : null;
        $verifiedStatistics = Schema::hasTable('report_statistics')
            ? ReportStatistic::query()
                ->published()
                ->with('fiscalYear')
                ->orderBy('display_order')
                ->orderByDesc('id')
                ->limit(4)
                ->get()
            : collect();
        $activeMemberCount = Schema::hasTable('members')
            ? Member::query()->where('status', 'active')->count()
            : null;

        return view('frontend.home', compact(
            'settings',
            'featuredNotices',
            'tickerNotices',
            'leadershipMessages',
            'latestReports',
            'latestAnnualReport',
            'verifiedStatistics',
            'activeMemberCount',
        ));
    }

    public function showLeadershipMessage(string $leader): View
    {
        $messages = config('leadership.messages');
        abort_unless(array_key_exists($leader, $messages), 404);

        $message = $messages[$leader];
        $message['name'] = Setting::value($message['name_setting']);
        $portraitPath = Setting::value($message['portrait_setting']);
        $message['has_portrait'] = is_string($portraitPath) && Storage::disk('local')->exists($portraitPath);

        return view('frontend.leadership-message', compact('leader', 'message'));
    }

    public function leadershipPortrait(string $leader): BinaryFileResponse
    {
        $messages = config('leadership.messages');
        abort_unless(array_key_exists($leader, $messages), 404);

        $portraitPath = Setting::value($messages[$leader]['portrait_setting']);
        abort_unless(is_string($portraitPath) && Storage::disk('local')->exists($portraitPath), 404);

        return response()->file(Storage::disk('local')->path($portraitPath), [
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function about(): View
    {
        return view('frontend.about');
    }

    public function boardOfDirectors(): View
    {
        return view('frontend.about-board-of-directors');
    }

    public function otherCommittees(): View
    {
        return view('frontend.about-other-committees');
    }

    public function employees(): View
    {
        return view('frontend.about-employees');
    }

    public function services(): View
    {
        $savings = Schema::hasTable('services')
            ? Service::query()->active()->savings()->orderBy('display_order')->get()
            : collect();
        $loans = Schema::hasTable('services')
            ? Service::query()->active()->loans()->orderBy('display_order')->get()
            : collect();
        $memberSupport = Schema::hasTable('services')
            ? Service::query()->active()->memberSupport()->orderBy('display_order')->get()
            : collect();
        $faqs = Schema::hasTable('faqs')
            ? Faq::query()->active()->orderBy('display_order')->get()
            : collect();

        return view('frontend.services', compact('savings', 'loans', 'memberSupport', 'faqs'));
    }

    public function showService(Service $service): View
    {
        abort_unless($service->is_active, 404);

        $relatedServices = Service::query()
            ->active()
            ->where('category', $service->category)
            ->where('id', '!=', $service->id)
            ->orderBy('display_order')
            ->limit(3)
            ->get();

        return view('frontend.service-detail', compact('service', 'relatedServices'));
    }

    public function savings(): View
    {
        return view('frontend.savings');
    }

    public function loans(): View
    {
        return view('frontend.loans');
    }

    public function notices(Request $request): View
    {
        if (! Schema::hasTable('notices')) {
            return view('frontend.notices', [
                'notices' => collect(),
                'filters' => [],
            ]);
        }

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', Rule::in(Notice::CATEGORIES)],
            'published_on' => ['nullable', 'date'],
        ]);

        $query = Notice::query()->activePublished();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title_np', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('body_np', 'like', "%{$search}%")
                    ->orWhere('body_en', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (! empty($filters['published_on'])) {
            $query->whereDate('published_at', $filters['published_on']);
        }

        $notices = $query
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        return view('frontend.notices', compact('notices', 'filters'));
    }

    public function showNotice(Notice $notice): View
    {
        $notice = Notice::query()
            ->activePublished()
            ->whereKey($notice->getKey())
            ->firstOrFail();
        $relatedNotices = Notice::query()
            ->activePublished()
            ->where('category', $notice->category)
            ->where('id', '!=', $notice->getKey())
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        return view('frontend.notice', compact('notice', 'relatedNotices'));
    }

    public function noticeDocument(Notice $notice): StreamedResponse
    {
        $notice = $this->publishedNoticeWithDocument($notice);

        return Storage::disk('local')->response(
            $notice->document_path,
            null,
            [
                'Content-Type' => $notice->document_mime_type,
                'X-Content-Type-Options' => 'nosniff',
            ],
            'inline',
        );
    }

    public function downloadNoticeDocument(Notice $notice): StreamedResponse
    {
        $notice = $this->publishedNoticeWithDocument($notice);
        $extension = match ($notice->document_mime_type) {
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => abort(404),
        };

        return Storage::disk('local')->download(
            $notice->document_path,
            'notice-'.$notice->id.'.'.$extension,
            [
                'Content-Type' => $notice->document_mime_type,
                'X-Content-Type-Options' => 'nosniff',
            ],
        );
    }

    private function publishedNoticeWithDocument(Notice $notice): Notice
    {
        $notice = Notice::query()
            ->activePublished()
            ->whereKey($notice->getKey())
            ->firstOrFail();

        abort_unless(
            is_string($notice->document_path)
                && is_string($notice->document_mime_type)
                && in_array($notice->document_mime_type, ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'], true)
                && Storage::disk('local')->exists($notice->document_path),
            404,
        );

        return $notice;
    }

    public function contact(): View
    {
        return view('frontend.contact');
    }
}
