<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="max-w-4xl space-y-6 rounded-xl border border-[#DCE9DF] bg-white p-5 shadow-sm sm:p-8">
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
        <div class="sm:col-span-2">
            <label for="document" class="mb-2 block text-sm font-semibold text-[#182A22]">Notice image or PDF</label>
            <input id="document" type="file" name="document" accept=".pdf,.jpg,.jpeg,.png,.webp" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] file:mr-4 file:rounded-full file:border-0 file:bg-[#EFF8F1] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#08713D]">
            <p class="mt-2 text-xs text-[#617067]">PDF, JPG, PNG, or WebP; maximum size 10 MB.</p>
            @error('document') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
            @if ($notice->document_path)
                <p class="mt-3 text-sm font-medium text-[#08713D]">Current file: {{ strtoupper(str($notice->document_mime_type)->after('/')->value()) }}</p>
                <label class="mt-3 inline-flex items-center gap-2 text-sm text-[#344054]">
                    <input type="checkbox" name="remove_document" value="1" class="rounded border-[#B6C9BC] text-[#08713D] focus:ring-[#08713D]">
                    Remove current file
                </label>
            @endif
        </div>
        <label class="flex items-start gap-3 rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] p-4 text-sm text-[#344054]">
            <input type="checkbox" name="featured" value="1" @checked(old('featured', $notice->featured)) class="mt-0.5 rounded border-[#B6C9BC] text-[#08713D] focus:ring-[#08713D]">
            <span><span class="block font-semibold text-[#182A22]">Pin important notice to the top</span><span class="mt-1 block text-xs text-[#617067]">Pinned notices appear before other published notices.</span></span>
        </label>
    </div>

    <div class="flex flex-wrap items-center gap-3 border-t border-[#E8EFEA] pt-5">
        <button type="submit" class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">{{ $method === 'POST' ? 'Create notice' : 'Save changes' }}</button>
        <a href="{{ route('admin.notices.index') }}" class="rounded-xl border border-[#DCE9DF] px-5 py-2.5 text-sm font-semibold text-[#344054] hover:bg-[#F5F8F6]">Cancel</a>
    </div>
</form>
