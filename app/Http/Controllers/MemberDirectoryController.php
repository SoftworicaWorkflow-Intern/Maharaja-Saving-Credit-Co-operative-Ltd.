<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MemberDirectoryController extends Controller
{
    public function __invoke(): View
    {
        $members = Member::query()
            ->where('status', 'active')
            ->select('id', 'user_id', 'member_number', 'joined_at')
            ->with('user:id,name')
            ->orderByDesc('joined_at')
            ->orderByDesc('id')
            ->paginate(20);

        return view('frontend.members.index', compact('members'));
    }

    public function showNumber(Member $member): JsonResponse
    {
        abort_unless($member->status === 'active', 404);

        return response()
            ->json(['member_number' => $member->member_number])
            ->header('Cache-Control', 'no-store, private');
    }
}
