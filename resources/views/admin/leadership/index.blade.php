@extends('layouts.admin')

@section('title', 'Leadership profiles')
@section('page-title', 'Leadership profiles')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-[#182A22]">Leadership profiles</h2>
    <p class="mt-1 max-w-3xl text-sm leading-6 text-[#617067]">Add verified names and upload each leader’s own portrait. Names are optional; when left blank, the public site displays the designation only. Portraits are kept separate for each profile.</p>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $errors->first() }}</div>
@endif

<div class="grid gap-5 xl:grid-cols-2">
    @foreach ($profiles as $leader => $profile)
        <section class="rounded-2xl border border-[#DCE9DF] bg-white p-5 sm:p-6">
            <div class="mb-5">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#08713D]">{{ $profile['label'] }}</p>
                <h3 class="mt-2 text-xl font-bold text-[#182A22]">{{ $profile['designation'] }}</h3>
            </div>
            <form method="POST" action="{{ route('admin.leadership.update', $leader) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="{{ $leader }}-name" class="mb-1.5 block text-sm font-medium text-[#182A22]">Verified name <span class="font-normal text-[#617067]">(optional)</span></label>
                    <input id="{{ $leader }}-name" name="name" type="text" maxlength="255" value="{{ old('name', $profile['name']) }}" autocomplete="name" class="w-full rounded-xl border border-[#DCE9DF] px-4 py-3 text-sm text-[#182A22] focus:border-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
                    @error('name')<p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="{{ $leader }}-portrait" class="mb-1.5 block text-sm font-medium text-[#182A22]">Portrait photo <span class="font-normal text-[#617067]">(JPG, PNG, or WebP; up to 5 MB)</span></label>
                    <input id="{{ $leader }}-portrait" name="portrait" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-[#DCE9DF] bg-white text-sm text-[#344054] file:mr-4 file:border-0 file:bg-[#EFF8F1] file:px-4 file:py-3 file:font-semibold file:text-[#04552F] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
                    @error('portrait')<p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>@enderror
                    @if ($profile['has_portrait'])
                        <img src="{{ route('leadership.portrait', $leader) }}" alt="Current portrait of {{ $profile['name'] ?: $profile['designation'] }}" class="mt-4 h-40 w-full rounded-xl object-cover shadow-sm">
                    @else
                        <p class="mt-2 text-xs text-[#617067]">No portrait uploaded; the public site shows a neutral placeholder.</p>
                    @endif
                </div>
                <button type="submit" class="rounded-xl bg-[#08713D] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#04552F] focus:outline-none focus:ring-2 focus:ring-[#FFD91A] focus:ring-offset-2">Save {{ $profile['designation'] }} profile</button>
            </form>
        </section>
    @endforeach
</div>
@endsection
