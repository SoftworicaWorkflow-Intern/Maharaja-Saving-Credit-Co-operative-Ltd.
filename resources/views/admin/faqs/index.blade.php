@extends('layouts.admin')

@section('title', __('FAQs'))
@section('page-title', __('Frequently Asked Questions'))

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-[#182A22]">Frequently Asked Questions</h2>
        <p class="mt-1 text-sm text-[#617067]">Manage questions and verified answers displayed in the FAQ accordion on the Services page.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('services') }}#faqs" target="_blank" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm font-semibold text-[#08713D] hover:bg-[#F5F8F6]">
            View FAQs on Services Page ↗
        </a>
        <a href="{{ route('admin.faqs.create') }}" class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">
            + Add New FAQ
        </a>
    </div>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
@endif

<form method="GET" action="{{ route('admin.faqs.index') }}" class="mb-5 grid gap-3 rounded-xl border border-[#DCE9DF] bg-white p-4 sm:grid-cols-[1fr_220px_auto]">
    <label for="faq-search" class="sr-only">Search questions or answers</label>
    <input id="faq-search" name="search" value="{{ request('search') }}" placeholder="Search English or Nepali questions/answers" class="rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-2.5 text-sm text-[#182A22]">

    <label for="faq-category" class="sr-only">Filter by category</label>
    <select id="faq-category" name="category" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm text-[#182A22]">
        <option value="">All Categories</option>
        <option value="general" @selected(request('category') === 'general')>General</option>
        <option value="savings" @selected(request('category') === 'savings')>Savings</option>
        <option value="loan" @selected(request('category') === 'loan')>Loan</option>
        <option value="membership" @selected(request('category') === 'membership')>Membership</option>
    </select>

    <button type="submit" class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">Filter</button>
</form>

<div class="overflow-hidden rounded-xl border border-[#DCE9DF] bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-[#E8EFEA] text-left text-sm">
            <thead class="bg-[#F5F8F6] text-xs uppercase tracking-wide text-[#617067]">
                <tr>
                    <th class="px-5 py-3">Order</th>
                    <th class="px-5 py-3">Question & Answer</th>
                    <th class="px-5 py-3">Category</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E8EFEA]">
                @forelse ($faqs as $faq)
                    <tr class="hover:bg-[#F9FBFA]">
                        <td class="whitespace-nowrap px-5 py-4 font-mono text-xs text-[#617067]">#{{ $faq->display_order }}</td>
                        <td class="min-w-72 px-5 py-4">
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="font-semibold text-[#08713D] hover:underline">
                                {{ $faq->question_en }}
                            </a>
                            @if ($faq->question_np)
                                <p lang="ne" class="mt-0.5 text-xs text-[#617067]">{{ $faq->question_np }}</p>
                            @endif
                            <p class="mt-1 line-clamp-2 text-xs text-[#617067]">{{ $faq->answer_en }}</p>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <span class="rounded-full bg-[#E7F4EB] px-3 py-1 text-xs font-semibold text-[#04552F] capitalize">
                                {{ $faq->category }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            @if ($faq->is_active)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#E7F4EB] px-3 py-1 text-xs font-semibold text-[#04552F]">
                                    <span class="h-1.5 w-1.5 rounded-full bg-[#08713D]"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-right">
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="font-semibold text-[#08713D] hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="ml-3 inline" onsubmit="return confirm('Are you sure you want to delete this FAQ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold text-red-700 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sm text-[#617067]">
                            No FAQs found matching the criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($faqs->hasPages())
        <div class="border-t border-[#E8EFEA] px-5 py-4">{{ $faqs->links() }}</div>
    @endif
</div>
@endsection

