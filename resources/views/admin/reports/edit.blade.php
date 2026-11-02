@extends('layouts.admin')

@php($isAnnualReport = (string) $report->category_id === (string) $annualCategoryId)
@section('title', $isAnnualReport ? __('Edit Annual Report') : __('Edit Report'))
@section('page-title', $isAnnualReport ? __('Annual Reports Management') : __('Reports Management'))

@section('content')
<div class="mb-6">
    <a href="{{ $isAnnualReport ? route('admin.annual-reports.index') : route('admin.reports.index') }}" class="text-sm font-semibold text-[#14532D] hover:underline">← {{ $isAnnualReport ? __('Annual Reports Dashboard') : __('Reports Dashboard') }}</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">{{ $isAnnualReport ? __('Edit Annual Report') : __('Edit report') }}</h2>
    <p class="mt-1 text-sm text-[#617067]">{{ $report->title_en }}</p>
</div>

@if (session('status'))
    <div role="status" class="mb-5 max-w-4xl rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#14532D]">{{ session('status') }}</div>
@endif

@include('admin.reports.form', ['action' => route('admin.reports.update', $report), 'method' => 'PUT'])
@endsection
