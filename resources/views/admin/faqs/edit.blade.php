@extends('layouts.admin')

@section('title', __('Edit FAQ'))
@section('page-title', __('Edit FAQ'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.faqs.index') }}" class="text-sm font-semibold text-[#08713D] hover:underline">← All FAQs</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">Edit FAQ</h2>
    <p class="mt-1 text-sm text-[#617067]">Update question text, category, or answer.</p>
</div>

@include('admin.faqs.form', [
    'faq' => $faq,
    'action' => route('admin.faqs.update', $faq),
    'method' => 'PUT'
])
@endsection

