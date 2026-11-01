@extends('layouts.admin')

@section('title', __('Add Service'))
@section('page-title', __('Add Service'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.services.index') }}" class="text-sm font-semibold text-[#08713D] hover:underline">← All services</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">Create a New Service</h2>
    <p class="mt-1 text-sm text-[#617067]">Add a new savings product, loan scheme, or member support service.</p>
</div>

@include('admin.services.form', [
    'service' => $service,
    'action' => route('admin.services.store'),
    'method' => 'POST'
])
@endsection

