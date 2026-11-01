@extends('layouts.admin')

@section('title', __('Edit Service: ') . $service->title_en)
@section('page-title', __('Edit Service'))

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <a href="{{ route('admin.services.index') }}" class="text-sm font-semibold text-[#08713D] hover:underline">← All services</a>
        <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">Edit: {{ $service->title_en }}</h2>
        <p class="mt-1 text-sm text-[#617067]">Update service details, benefits, eligibility, or publication status.</p>
    </div>
    <a href="{{ route('services.show', $service->slug) }}" target="_blank" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2 text-sm font-semibold text-[#08713D] hover:bg-[#F5F8F6]">
        View Live Detail Page ↗
    </a>
</div>

@include('admin.services.form', [
    'service' => $service,
    'action' => route('admin.services.update', $service),
    'method' => 'PUT'
])
@endsection

