<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

        return view('frontend.home', compact('settings', 'featuredNotices', 'tickerNotices', 'leadershipMessages'));
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

    public function visionMission(): View
    {
        return view('frontend.about-vision-mission');
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
            return view('frontend.notices', ['notices' => collect()]);
        }

        $query = Notice::query()->activePublished();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title_np', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('body_np', 'like', "%{$search}%")
                    ->orWhere('body_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $notices = $query->orderByDesc('published_at')->paginate(7);

        return view('frontend.notices', compact('notices'));
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

    public function contact(): View
    {
        return view('frontend.contact');
    }
}
