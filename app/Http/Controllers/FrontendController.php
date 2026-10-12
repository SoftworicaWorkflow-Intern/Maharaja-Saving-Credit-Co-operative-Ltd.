<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FrontendController extends Controller
{
    public function home()
    {
        $settings = Schema::hasTable('settings') ? Setting::values() : [];

        $tickerNotices = Schema::hasTable('notices')
            ? Notice::query()
                ->activePublished()
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(10)
                ->get()
            : collect();

        $featuredNotices = $tickerNotices->take(3);

        return view('frontend.home', compact('settings', 'featuredNotices', 'tickerNotices'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function savings()
    {
        return view('frontend.savings');
    }

    public function loans()
    {
        return view('frontend.loans');
    }

    public function notices(Request $request)
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

        $notices = $query->orderByDesc('published_at')->paginate(9);

        return view('frontend.notices', compact('notices'));
    }

    public function showNotice(Notice $notice)
    {
        $notice = Notice::query()
            ->activePublished()
            ->whereKey($notice->getKey())
            ->firstOrFail();

        return view('frontend.notice', compact('notice'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }
}
