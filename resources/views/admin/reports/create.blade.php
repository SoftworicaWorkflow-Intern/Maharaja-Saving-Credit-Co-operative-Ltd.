@extends('layouts.admin')

@php($isAnnualReport = (string) $report->category_id === (string) $annualCategoryId)
@section('title', $isAnnualReport ? __('Create Annual Report') : __('Add New Report'))
@section('page-title', $isAnnualReport ? __('Create Annual Report') : __('Add New Report'))

@section('content')
<div class="mb-6">
    <a href="{{ $isAnnualReport ? route('admin.annual-reports.index') : route('admin.reports.index') }}" class="text-sm font-semibold text-[#14532D] hover:underline">← {{ $isAnnualReport ? __('Annual Reports Dashboard') : __('Reports Dashboard') }}</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">{{ $isAnnualReport ? __('Create Annual Report') : __('Add New Report') }}</h2>
    <p class="mt-1 text-sm text-[#617067]">{{ $isAnnualReport ? __('Prepare annual report chapters and only publish figures approved against institutional records.') : __('Add an official publication. Only published reports are visible to visitors.') }}</p>
</div>

@include('admin.reports.form', ['action' => route('admin.reports.store'), 'method' => 'POST'])
@endsection
