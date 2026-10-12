<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Requests\UpdateMemberStatusRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['active', 'suspended'])],
            'registered_from' => ['nullable', 'date'],
            'registered_to' => ['nullable', 'date', 'after_or_equal:registered_from'],
        ]);

        $query = Member::query()->with('user:id,name,email');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search): void {
                $query->where('member_number', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search): void {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['registered_from'])) {
            $query->whereDate('joined_at', '>=', $filters['registered_from']);
        }

        if (! empty($filters['registered_to'])) {
            $query->whereDate('joined_at', '<=', $filters['registered_to']);
        }

        $members = $query
            ->orderByDesc('joined_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.members.index', compact('members', 'filters'));
    }

    public function show(Member $member): View
    {
        $member->load([
            'user:id,name,email',
            'membershipApplication.events' => fn ($query) => $query->with('actor:id,name')->orderByDesc('created_at'),
            'membershipApplication.documents',
        ]);

        return view('admin.members.show', compact('member'));
    }

    public function update(UpdateMemberRequest $request, Member $member)
    {
        $member->fill($request->validated());
        $updatedFields = array_keys($member->getDirty());
        $member->save();

        if ($updatedFields !== []) {
            $member->membershipApplication->events()->create([
                'actor_id' => $request->user()->id,
                'event' => 'member_details_updated',
                'from_status' => $member->membershipApplication->status,
                'to_status' => $member->membershipApplication->status,
                'note' => 'Updated fields: '.implode(', ', $updatedFields),
            ]);
        }

        return redirect()->route('admin.members.show', $member)->with('status', 'Member details updated.');
    }

    public function updateStatus(UpdateMemberStatusRequest $request, Member $member)
    {
        $newStatus = $request->validated('status');

        if ($member->status === $newStatus) {
            return redirect()->route('admin.members.show', $member)
                ->with('status', 'The member already has that status.');
        }

        $previousStatus = $member->status;
        $member->status = $newStatus;
        $member->save();

        $member->membershipApplication->events()->create([
            'actor_id' => $request->user()->id,
            'event' => 'member_status_changed',
            'from_status' => $previousStatus,
            'to_status' => $newStatus,
            'note' => $request->validated('reason'),
        ]);

        return redirect()->route('admin.members.show', $member)->with('status', 'Member status updated.');
    }
}
