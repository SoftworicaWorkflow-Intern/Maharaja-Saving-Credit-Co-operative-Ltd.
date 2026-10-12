<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $administrator = $request->user();
        $operationalStats = [];

        if ($administrator->hasPermissionTo('applications.view')) {
            $operationalStats[] = [
                'label' => __('Open membership applications'),
                'count' => MembershipApplication::query()
                    ->whereIn('status', MembershipApplication::OPEN_STATUSES)
                    ->count(),
                'description' => __('Applications awaiting review or additional documents.'),
                'route' => 'admin.applications.index',
            ];
        }

        if ($administrator->hasPermissionTo('members.view')) {
            $operationalStats[] = [
                'label' => __('Active members'),
                'count' => Member::query()->where('status', 'active')->count(),
                'description' => __('Members with an active cooperative account.'),
                'route' => 'admin.members.index',
            ];
        }

        if ($administrator->hasPermissionTo('contact_inquiries.manage')) {
            $operationalStats[] = [
                'label' => __('Unreviewed inquiries'),
                'count' => ContactInquiry::query()->whereNull('reviewed_at')->count(),
                'description' => __('Messages waiting for a staff response.'),
                'route' => 'admin.contact-inquiries.index',
            ];
        }

        if ($administrator->hasPermissionTo('notices.manage')) {
            $operationalStats[] = [
                'label' => __('Published notices'),
                'count' => Notice::query()->activePublished()->count(),
                'description' => __('Announcements currently visible to website visitors.'),
                'route' => 'admin.notices.index',
            ];
        }

        return view('admin.dashboard', [
            'administrator' => $administrator,
            'operationalStats' => $operationalStats,
        ]);
    }
}
