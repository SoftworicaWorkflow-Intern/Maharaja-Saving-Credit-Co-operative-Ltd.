@extends('layouts.admin')

@section('title', __('Create notice'))
@section('page-title', __('Create notice'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.notices.index') }}" class="text-sm font-semibold text-[#08713D] hover:underline">← All notices</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">Create a notice</h2>
    <p class="mt-1 text-sm text-[#617067]">Published notices appear in the homepage ticker and public notices list.</p>
</div>

@include('admin.notices.form', ['notice' => $notice, 'action' => route('admin.notices.store'), 'method' => 'POST'])
@endsection
