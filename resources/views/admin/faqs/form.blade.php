<form method="POST" action="{{ $action }}" class="max-w-4xl space-y-6 rounded-xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-8">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="category" class="mb-2 block text-sm font-semibold text-[#182A22]">Category *</label>
            <select id="category" name="category" required class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
                <option value="general" @selected(old('category', $faq->category ?: 'general') === 'general')>General</option>
                <option value="membership" @selected(old('category', $faq->category) === 'membership')>Membership</option>
                <option value="savings" @selected(old('category', $faq->category) === 'savings')>Savings</option>
                <option value="loan" @selected(old('category', $faq->category) === 'loan')>Loan</option>
            </select>
            @error('category') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="display_order" class="mb-2 block text-sm font-semibold text-[#182A22]">Display Order</label>
            <input id="display_order" type="number" name="display_order" value="{{ old('display_order', $faq->display_order ?? 0) }}" min="0" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
            <p class="mt-1 text-xs text-[#617067]">Lower numbers display first (e.g. 1, 2, 3).</p>
            @error('display_order') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="question_en" class="mb-2 block text-sm font-semibold text-[#182A22]">Question (English) *</label>
            <input id="question_en" name="question_en" value="{{ old('question_en', $faq->question_en) }}" required maxlength="255" placeholder="e.g. How can I become a member of the cooperative?" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
            @error('question_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="question_np" class="mb-2 block text-sm font-semibold text-[#182A22]">प्रश्न (Nepali Question)</label>
            <input id="question_np" name="question_np" lang="ne" value="{{ old('question_np', $faq->question_np) }}" maxlength="255" placeholder="जस्तै: संस्थाको सदस्य कसरी बन्न सकिन्छ?" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
            @error('question_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="answer_en" class="mb-2 block text-sm font-semibold text-[#182A22]">Answer (English) *</label>
            <textarea id="answer_en" name="answer_en" rows="5" required placeholder="Provide clear, verified institutional guidelines..." class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('answer_en', $faq->answer_en) }}</textarea>
            @error('answer_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="answer_np" class="mb-2 block text-sm font-semibold text-[#182A22]">उत्तर (Nepali Answer)</label>
            <textarea id="answer_np" name="answer_np" lang="ne" rows="5" placeholder="संस्थाको नियम तथा कार्यविधि अनुसार आधिकारिक जानकारी..." class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('answer_np', $faq->answer_np) }}</textarea>
            @error('answer_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label class="flex items-center gap-3 rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] p-4">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $faq->is_active ?? true)) class="h-4 w-4 rounded border-[#B6C9BC] text-[#08713D] focus:ring-[#08713D]">
                <div>
                    <span class="block text-sm font-semibold text-[#182A22]">Active & Visible in FAQ accordion</span>
                    <span class="text-xs text-[#617067]">When unchecked, this FAQ will not be shown on the public site.</span>
                </div>
            </label>
            @error('is_active') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3 border-t border-[#E8EFEA] pt-5">
        <button type="submit" class="rounded-xl bg-[#08713D] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">
            {{ $method === 'POST' ? 'Create FAQ' : 'Save Changes' }}
        </button>
        <a href="{{ route('admin.faqs.index') }}" class="rounded-xl border border-[#DCE9DF] px-5 py-2.5 text-sm font-semibold text-[#344054] hover:bg-[#F5F8F6]">
            Cancel
        </a>
    </div>
</form>

