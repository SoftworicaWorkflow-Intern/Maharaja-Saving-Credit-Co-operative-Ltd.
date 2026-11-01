<form method="POST" action="{{ $action }}" class="max-w-5xl space-y-8 rounded-xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-8">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- Category, Status & Display Order --}}
    <div class="grid gap-5 sm:grid-cols-3">
        <div>
            <label for="category" class="mb-2 block text-sm font-semibold text-[#182A22]">Service Category *</label>
            <select id="category" name="category" required class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
                <option value="savings" @selected(old('category', $service->category) === 'savings')>Savings Scheme</option>
                <option value="loan" @selected(old('category', $service->category) === 'loan')>Loan Scheme</option>
                <option value="member_support" @selected(old('category', $service->category) === 'member_support')>Member Support Service</option>
            </select>
            @error('category') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="display_order" class="mb-2 block text-sm font-semibold text-[#182A22]">Display Order</label>
            <input id="display_order" type="number" name="display_order" value="{{ old('display_order', $service->display_order ?? 0) }}" min="0" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
            <p class="mt-1 text-xs text-[#617067]">Lower numbers display first (e.g. 1, 2, 3).</p>
            @error('display_order') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="icon" class="mb-2 block text-sm font-semibold text-[#182A22]">Icon Key (optional)</label>
            <input id="icon" name="icon" value="{{ old('icon', $service->icon) }}" placeholder="e.g. pig, calendar, shield, building" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
            @error('icon') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Title and Slug --}}
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="title_en" class="mb-2 block text-sm font-semibold text-[#182A22]">English Title *</label>
            <input id="title_en" name="title_en" value="{{ old('title_en', $service->title_en) }}" required maxlength="255" placeholder="e.g. Regular Savings" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
            @error('title_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="title_np" class="mb-2 block text-sm font-semibold text-[#182A22]">नेपाली शीर्षक (Nepali Title)</label>
            <input id="title_np" name="title_np" lang="ne" value="{{ old('title_np', $service->title_np) }}" maxlength="255" placeholder="जस्तै: साधारण बचत" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
            @error('title_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="slug" class="mb-2 block text-sm font-semibold text-[#182A22]">URL Slug (optional - auto-generated if left blank)</label>
            <input id="slug" name="slug" value="{{ old('slug', $service->slug) }}" maxlength="255" placeholder="e.g. regular-savings" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
            <p class="mt-1 text-xs text-[#617067]">Used in public URL: {{ url('/services') }}/[slug]</p>
            @error('slug') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Short Description --}}
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="short_description_en" class="mb-2 block text-sm font-semibold text-[#182A22]">Short Description (English) *</label>
            <textarea id="short_description_en" name="short_description_en" rows="3" required class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('short_description_en', $service->short_description_en) }}</textarea>
            <p class="mt-1 text-xs text-[#617067]">Displayed on service cards on the main Services page.</p>
            @error('short_description_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="short_description_np" class="mb-2 block text-sm font-semibold text-[#182A22]">संक्षिप्त विवरण (Nepali Short Description)</label>
            <textarea id="short_description_np" name="short_description_np" lang="ne" rows="3" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('short_description_np', $service->short_description_np) }}</textarea>
            @error('short_description_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Full Description --}}
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="full_description_en" class="mb-2 block text-sm font-semibold text-[#182A22]">Full Description (English)</label>
            <textarea id="full_description_en" name="full_description_en" rows="5" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('full_description_en', $service->full_description_en) }}</textarea>
            <p class="mt-1 text-xs text-[#617067]">Detailed overview shown on the dedicated service detail page.</p>
            @error('full_description_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="full_description_np" class="mb-2 block text-sm font-semibold text-[#182A22]">विस्तृत विवरण (Nepali Full Description)</label>
            <textarea id="full_description_np" name="full_description_np" lang="ne" rows="5" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('full_description_np', $service->full_description_np) }}</textarea>
            @error('full_description_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Purpose --}}
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="purpose_en" class="mb-2 block text-sm font-semibold text-[#182A22]">Purpose / Target Use (English)</label>
            <textarea id="purpose_en" name="purpose_en" rows="3" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('purpose_en', $service->purpose_en) }}</textarea>
            @error('purpose_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="purpose_np" class="mb-2 block text-sm font-semibold text-[#182A22]">उद्देश्य (Nepali Purpose)</label>
            <textarea id="purpose_np" name="purpose_np" lang="ne" rows="3" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('purpose_np', $service->purpose_np) }}</textarea>
            @error('purpose_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Key Benefits (One per line) --}}
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="benefits_en_text" class="mb-2 block text-sm font-semibold text-[#182A22]">Key Benefits (English - One per line)</label>
            <textarea id="benefits_en_text" name="benefits_en_text" rows="4" placeholder="Competitive interest calculation&#10;Flexible deposits and withdrawals&#10;Annual statement facility" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 font-mono text-xs text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('benefits_en_text', is_array($service->benefits_en) ? implode("\n", $service->benefits_en) : '') }}</textarea>
            <p class="mt-1 text-xs text-[#617067]">Enter each benefit on a new line.</p>
            @error('benefits_en_text') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="benefits_np_text" class="mb-2 block text-sm font-semibold text-[#182A22]">मुख्य सुविधाहरू (Nepali Benefits - प्रति हरफ एक)</label>
            <textarea id="benefits_np_text" name="benefits_np_text" lang="ne" rows="4" placeholder="दैनिक मौज्दातमा उचित ब्याज&#10;सजिलो जम्मा तथा भुक्तानी" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 font-mono text-xs text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('benefits_np_text', is_array($service->benefits_np) ? implode("\n", $service->benefits_np) : '') }}</textarea>
            @error('benefits_np_text') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Eligibility Requirements --}}
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="eligibility_en" class="mb-2 block text-sm font-semibold text-[#182A22]">Eligibility Requirements (English)</label>
            <textarea id="eligibility_en" name="eligibility_en" rows="3" placeholder="Cooperative membership and valid Nepali citizenship or legal guardian identification." class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('eligibility_en', $service->eligibility_en) }}</textarea>
            @error('eligibility_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="eligibility_np" class="mb-2 block text-sm font-semibold text-[#182A22]">योग्यता / मापदण्ड (Nepali Eligibility)</label>
            <textarea id="eligibility_np" name="eligibility_np" lang="ne" rows="3" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('eligibility_np', $service->eligibility_np) }}</textarea>
            @error('eligibility_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Required Documents (One per line) --}}
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="required_documents_en_text" class="mb-2 block text-sm font-semibold text-[#182A22]">Required Documents (English - One per line)</label>
            <textarea id="required_documents_en_text" name="required_documents_en_text" rows="4" placeholder="Citizenship certificate copy&#10;Recent passport size photographs (2 copies)&#10;Completed application form" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 font-mono text-xs text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('required_documents_en_text', is_array($service->required_documents_en) ? implode("\n", $service->required_documents_en) : '') }}</textarea>
            <p class="mt-1 text-xs text-[#617067]">Enter each required document on a new line.</p>
            @error('required_documents_en_text') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="required_documents_np_text" class="mb-2 block text-sm font-semibold text-[#182A22]">आवश्यक कागजातहरू (Nepali Documents - प्रति हरफ एक)</label>
            <textarea id="required_documents_np_text" name="required_documents_np_text" lang="ne" rows="4" placeholder="नागरिकता प्रमाणपत्रको प्रतिलिपि&#10;हालसालै खिचिएको २ प्रति पासपोर्ट साइज फोटो" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 font-mono text-xs text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('required_documents_np_text', is_array($service->required_documents_np) ? implode("\n", $service->required_documents_np) : '') }}</textarea>
            @error('required_documents_np_text') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Application Process --}}
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="process_en" class="mb-2 block text-sm font-semibold text-[#182A22]">Application Process (English)</label>
            <textarea id="process_en" name="process_en" rows="3" placeholder="Visit any branch or complete the online inquiry form, submit verification documents, and activate your facility upon approval." class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('process_en', $service->process_en) }}</textarea>
            @error('process_en') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="process_np" class="mb-2 block text-sm font-semibold text-[#182A22]">आवेदन प्रक्रिया (Nepali Process)</label>
            <textarea id="process_np" name="process_np" lang="ne" rows="3" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('process_np', $service->process_np) }}</textarea>
            @error('process_np') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Active Status Toggle --}}
    <div class="rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] p-4">
        <label class="flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true)) class="h-4 w-4 rounded border-[#B6C9BC] text-[#08713D] focus:ring-[#08713D]">
            <div>
                <span class="block text-sm font-semibold text-[#182A22]">Active & Visible to Public</span>
                <span class="text-xs text-[#617067]">When unchecked, this service will be hidden from the public website.</span>
            </div>
        </label>
        @error('is_active') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap items-center gap-3 border-t border-[#E8EFEA] pt-5">
        <button type="submit" class="rounded-xl bg-[#08713D] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">
            {{ $method === 'POST' ? 'Create Service' : 'Save Changes' }}
        </button>
        <a href="{{ route('admin.services.index') }}" class="rounded-xl border border-[#DCE9DF] px-5 py-2.5 text-sm font-semibold text-[#344054] hover:bg-[#F5F8F6]">
            Cancel
        </a>
    </div>
</form>

