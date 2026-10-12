<form method="POST" action="{{ $action }}" class="max-w-4xl space-y-6 rounded-xl border border-[#DCE9DF] bg-white p-5 shadow-sm sm:p-8">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="title_en" class="mb-2 block text-sm font-semibold text-[#182A22]">English title</label>
            <input id="title_en" name="title_en" value="{{ old('title_en', $notice->title_en) }}" required maxlength="255" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
            @error('title_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="title_np" class="mb-2 block text-sm font-semibold text-[#182A22]">नेपाली शीर्षक</label>
            <input id="title_np" name="title_np" lang="ne" value="{{ old('title_np', $notice->title_np) }}" required maxlength="255" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
            @error('title_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="body_en" class="mb-2 block text-sm font-semibold text-[#182A22]">English content</label>
            <textarea id="body_en" name="body_en" rows="7" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('body_en', $notice->body_en) }}</textarea>
            @error('body_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="body_np" class="mb-2 block text-sm font-semibold text-[#182A22]">नेपाली विवरण</label>
            <textarea id="body_np" name="body_np" lang="ne" rows="7" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('body_np', $notice->body_np) }}</textarea>
            @error('body_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="category" class="mb-2 block text-sm font-semibold text-[#182A22]">Category</label>
            <select id="category" name="category" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22]">
                @foreach (\App\Models\Notice::CATEGORIES as $category)
                    <option value="{{ $category }}" @selected(old('category', $notice->category ?: 'general') === $category)>{{ ucfirst($category) }}</option>
                @endforeach
            </select>
            @error('category') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="status" class="mb-2 block text-sm font-semibold text-[#182A22]">Publication status</label>
            <select id="status" name="status" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22]">
                @foreach (\App\Models\Notice::STATUSES as $status)
                    <option value="{{ $status }}" @selected(old('status', $notice->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            @error('status') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="published_at" class="mb-2 block text-sm font-semibold text-[#182A22]">Publish date and time (optional)</label>
            <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', $notice->published_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22]">
            <p class="mt-2 text-xs text-[#617067]">A future date schedules the notice; it becomes visible at that time.</p>
            @error('published_at') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <label class="flex items-start gap-3 rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] p-4 text-sm text-[#344054]">
            <input type="checkbox" name="featured" value="1" @checked(old('featured', $notice->featured)) class="mt-0.5 rounded border-[#B6C9BC] text-[#08713D] focus:ring-[#08713D]">
            <span><span class="block font-semibold text-[#182A22]">Featured notice</span><span class="mt-1 block text-xs text-[#617067]">Mark this notice as featured in the existing notice system.</span></span>
        </label>
    </div>

    <div class="flex flex-wrap items-center gap-3 border-t border-[#E8EFEA] pt-5">
        <button type="submit" class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">{{ $method === 'POST' ? 'Create notice' : 'Save changes' }}</button>
        <a href="{{ route('admin.notices.index') }}" class="rounded-xl border border-[#DCE9DF] px-5 py-2.5 text-sm font-semibold text-[#344054] hover:bg-[#F5F8F6]">Cancel</a>
    </div>
</form>
