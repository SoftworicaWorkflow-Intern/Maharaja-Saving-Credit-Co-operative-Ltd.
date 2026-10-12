@extends('layouts.admin')

@section('title', __('Edit notice'))
@section('page-title', __('Edit notice'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.notices.index') }}" class="text-sm font-semibold text-[#08713D] hover:underline">← All notices</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">Edit notice</h2>
    <p class="mt-1 text-sm text-[#617067]">Unpublish the notice to remove it from the public site and ticker.</p>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
@endif

@include('admin.notices.form', ['notice' => $notice, 'action' => route('admin.notices.update', $notice), 'method' => 'PUT'])
@endsection
