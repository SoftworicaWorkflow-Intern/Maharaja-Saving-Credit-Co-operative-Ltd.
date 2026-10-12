<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactInquiryController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['new', 'reviewed'])],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = ContactInquiry::query()->with('reviewer:id,name');

        if (($filters['status'] ?? null) === 'new') {
            $query->whereNull('reviewed_at');
        } elseif (($filters['status'] ?? null) === 'reviewed') {
            $query->whereNotNull('reviewed_at');
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $inquiries = $query
            ->orderByRaw('reviewed_at IS NOT NULL')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.contact-inquiries.index', compact('inquiries', 'filters'));
    }

    public function show(ContactInquiry $contactInquiry): View
    {
        $contactInquiry->load('reviewer:id,name');

        return view('admin.contact-inquiries.show', [
            'inquiry' => $contactInquiry,
        ]);
    }

    public function markReviewed(ContactInquiry $contactInquiry, Request $request): RedirectResponse
    {
        DB::transaction(function () use ($contactInquiry, $request): void {
            $inquiry = ContactInquiry::query()
                ->lockForUpdate()
                ->findOrFail($contactInquiry->id);

            if ($inquiry->reviewed_at === null) {
                $inquiry->forceFill([
                    'reviewed_by' => $request->user()->id,
                    'reviewed_at' => now(),
                ])->save();
            }
        });

        return redirect()
            ->route('admin.contact-inquiries.show', $contactInquiry)
            ->with('status', 'Inquiry marked as reviewed.');
    }
}
