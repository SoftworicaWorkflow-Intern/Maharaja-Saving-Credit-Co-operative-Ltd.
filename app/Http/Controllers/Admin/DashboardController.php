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
        $operationalCharts = [];

        if ($administrator->hasPermissionTo('applications.view')) {
            $applicationStatusCounts = MembershipApplication::query()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $operationalStats[] = [
                'label' => __('Open membership applications'),
                'count' => MembershipApplication::query()
                    ->whereIn('status', MembershipApplication::OPEN_STATUSES)
                    ->count(),
                'description' => __('Applications awaiting review or additional documents.'),
                'route' => 'admin.applications.index',
            ];
            $operationalCharts[] = [
                'title' => __('Membership applications'),
                'items' => [
                    ['label' => __('Pending'), 'value' => (int) ($applicationStatusCounts[MembershipApplication::PENDING] ?? 0)],
                    ['label' => __('Under review'), 'value' => (int) ($applicationStatusCounts[MembershipApplication::UNDER_REVIEW] ?? 0)],
                    ['label' => __('Documents required'), 'value' => (int) ($applicationStatusCounts[MembershipApplication::DOCUMENTS_REQUIRED] ?? 0)],
                    ['label' => __('Approved'), 'value' => (int) ($applicationStatusCounts[MembershipApplication::APPROVED] ?? 0)],
                    ['label' => __('Rejected'), 'value' => (int) ($applicationStatusCounts[MembershipApplication::REJECTED] ?? 0)],
                ],
            ];
        }

        if ($administrator->hasPermissionTo('members.view')) {
            $memberStatusCounts = Member::query()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $operationalStats[] = [
                'label' => __('Active members'),
                'count' => Member::query()->where('status', 'active')->count(),
                'description' => __('Members with an active cooperative account.'),
                'route' => 'admin.members.index',
            ];
            $operationalCharts[] = [
                'title' => __('Member accounts'),
                'items' => $memberStatusCounts
                    ->map(fn (int $total, string $status): array => [
                        'label' => __(ucfirst(str_replace('_', ' ', $status))),
                        'value' => $total,
                    ])
                    ->values()
                    ->all(),
            ];
        }

        if ($administrator->hasPermissionTo('contact_inquiries.manage')) {
            $reviewedInquiries = ContactInquiry::query()->whereNotNull('reviewed_at')->count();

            $operationalStats[] = [
                'label' => __('Unreviewed inquiries'),
                'count' => ContactInquiry::query()->whereNull('reviewed_at')->count(),
                'description' => __('Messages waiting for a staff response.'),
                'route' => 'admin.contact-inquiries.index',
            ];
            $operationalCharts[] = [
                'title' => __('Contact inquiries'),
                'items' => [
                    ['label' => __('Awaiting review'), 'value' => ContactInquiry::query()->whereNull('reviewed_at')->count()],
                    ['label' => __('Reviewed'), 'value' => $reviewedInquiries],
                ],
            ];
        }

        if ($administrator->hasPermissionTo('notices.manage')) {
            $noticeStatusCounts = Notice::query()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $operationalStats[] = [
                'label' => __('Published notices'),
                'count' => Notice::query()->activePublished()->count(),
                'description' => __('Announcements currently visible to website visitors.'),
                'route' => 'admin.notices.index',
            ];
            $operationalCharts[] = [
                'title' => __('Notice status'),
                'items' => [
                    ['label' => __('Draft'), 'value' => (int) ($noticeStatusCounts['draft'] ?? 0)],
                    ['label' => __('Published'), 'value' => (int) ($noticeStatusCounts['published'] ?? 0)],
                ],
            ];
        }

        return view('admin.dashboard', [
            'administrator' => $administrator,
            'operationalStats' => $operationalStats,
            'operationalCharts' => $operationalCharts,
        ]);
    }
}
