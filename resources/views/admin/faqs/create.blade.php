@extends('layouts.admin')

@section('title', __('Add FAQ'))
@section('page-title', __('Add FAQ'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.faqs.index') }}" class="text-sm font-semibold text-[#08713D] hover:underline">← All FAQs</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">Create a New FAQ</h2>
    <p class="mt-1 text-sm text-[#617067]">Add a frequently asked question and verified institutional answer.</p>
</div>

@include('admin.faqs.form', [
    'faq' => $faq,
    'action' => route('admin.faqs.store'),
    'method' => 'POST'
])
@endsection

