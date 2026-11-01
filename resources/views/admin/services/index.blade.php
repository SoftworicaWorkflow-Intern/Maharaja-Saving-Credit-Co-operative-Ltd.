@extends('layouts.admin')

@section('title', __('Services'))
@section('page-title', __('Financial Services'))

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-[#182A22]">Financial Services</h2>
        <p class="mt-1 text-sm text-[#617067]">Manage savings, loan schemes, and member support services displayed across the portal.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('services') }}" target="_blank" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm font-semibold text-[#08713D] hover:bg-[#F5F8F6]">
            View Public Services Page ↗
        </a>
        <a href="{{ route('admin.services.create') }}" class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">
            + Add New Service
        </a>
    </div>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
@endif

<form method="GET" action="{{ route('admin.services.index') }}" class="mb-5 grid gap-3 rounded-xl border border-[#DCE9DF] bg-white p-4 sm:grid-cols-[1fr_200px_160px_auto]">
    <label for="service-search" class="sr-only">Search services</label>
    <input id="service-search" name="search" value="{{ request('search') }}" placeholder="Search by title in English or Nepali" class="rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-2.5 text-sm text-[#182A22]">

    <label for="service-category" class="sr-only">Filter by category</label>
    <select id="service-category" name="category" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm text-[#182A22]">
        <option value="">All Categories</option>
        <option value="savings" @selected(request('category') === 'savings')>Savings</option>
        <option value="loan" @selected(request('category') === 'loan')>Loan</option>
        <option value="member_support" @selected(request('category') === 'member_support')>Member Support</option>
    </select>

    <label for="service-status" class="sr-only">Filter by status</label>
    <select id="service-status" name="status" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm text-[#182A22]">
        <option value="">All Statuses</option>
        <option value="active" @selected(request('status') === 'active')>Active</option>
        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
    </select>

    <button type="submit" class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">Filter</button>
</form>

<div class="overflow-hidden rounded-xl border border-[#DCE9DF] bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-[#E8EFEA] text-left text-sm">
            <thead class="bg-[#F5F8F6] text-xs uppercase tracking-wide text-[#617067]">
                <tr>
                    <th class="px-5 py-3">Order</th>
                    <th class="px-5 py-3">Service Name</th>
                    <th class="px-5 py-3">Category</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E8EFEA]">
                @forelse ($services as $service)
                    <tr class="hover:bg-[#F9FBFA]">
                        <td class="whitespace-nowrap px-5 py-4 font-mono text-xs text-[#617067]">#{{ $service->display_order }}</td>
                        <td class="min-w-64 px-5 py-4">
                            <a href="{{ route('admin.services.edit', $service) }}" class="font-semibold text-[#08713D] hover:underline">
                                {{ $service->title_en }}
                            </a>
                            @if ($service->title_np)
                                <p lang="ne" class="mt-0.5 text-xs text-[#617067]">{{ $service->title_np }}</p>
                            @endif
                            <p class="mt-1 line-clamp-1 text-xs text-[#617067]">{{ $service->short_description_en }}</p>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            @if ($service->category === 'savings')
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">Savings</span>
                            @elseif ($service->category === 'loan')
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">Loan</span>
                            @else
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">Member Support</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            @if ($service->is_active)
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
                            <a href="{{ route('services.show', $service->slug) }}" target="_blank" class="font-semibold text-[#617067] hover:text-[#08713D] hover:underline">View</a>
                            <a href="{{ route('admin.services.edit', $service) }}" class="ml-3 font-semibold text-[#08713D] hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="ml-3 inline" onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold text-red-700 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sm text-[#617067]">
                            No services found matching the criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($services->hasPages())
        <div class="border-t border-[#E8EFEA] px-5 py-4">{{ $services->links() }}</div>
    @endif
</div>
@endsection

