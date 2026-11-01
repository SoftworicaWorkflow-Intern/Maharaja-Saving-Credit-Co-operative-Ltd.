<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Service::query();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_np', 'like', "%{$search}%");
            });
        }

        $services = $query
            ->orderBy('category')
            ->orderBy('display_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create', [
            'service' => new Service(['is_active' => true, 'display_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateService($request);
        $service = Service::query()->create($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'Service created successfully.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $this->validateService($request, $service->id);
        $service->update($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'Service deleted successfully.');
    }

    private function validateService(Request $request, ?int $id = null): array
    {
        $validated = $request->validate([
            'category' => ['required', 'string', Rule::in(['savings', 'loan', 'member_support'])],
            'title_en' => ['required', 'string', 'max:255'],
            'title_np' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('services', 'slug')->ignore($id)],
            'short_description_en' => ['required', 'string'],
            'short_description_np' => ['nullable', 'string'],
            'full_description_en' => ['nullable', 'string'],
            'full_description_np' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'benefits_en_text' => ['nullable', 'string'],
            'benefits_np_text' => ['nullable', 'string'],
            'eligibility_en' => ['nullable', 'string'],
            'eligibility_np' => ['nullable', 'string'],
            'required_documents_en_text' => ['nullable', 'string'],
            'required_documents_np_text' => ['nullable', 'string'],
            'purpose_en' => ['nullable', 'string'],
            'purpose_np' => ['nullable', 'string'],
            'process_en' => ['nullable', 'string'],
            'process_np' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title_en']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['display_order'] = (int) ($validated['display_order'] ?? 0);

        // Convert newline-separated text to JSON arrays
        if (!empty($request->input('benefits_en_text'))) {
            $validated['benefits_en'] = array_values(array_filter(array_map('trim', explode("\n", $request->input('benefits_en_text')))));
        }
        if (!empty($request->input('benefits_np_text'))) {
            $validated['benefits_np'] = array_values(array_filter(array_map('trim', explode("\n", $request->input('benefits_np_text')))));
        }
        if (!empty($request->input('required_documents_en_text'))) {
            $validated['required_documents_en'] = array_values(array_filter(array_map('trim', explode("\n", $request->input('required_documents_en_text')))));
        }
        if (!empty($request->input('required_documents_np_text'))) {
            $validated['required_documents_np'] = array_values(array_filter(array_map('trim', explode("\n", $request->input('required_documents_np_text')))));
        }

        unset(
            $validated['benefits_en_text'],
            $validated['benefits_np_text'],
            $validated['required_documents_en_text'],
            $validated['required_documents_np_text']
        );

        return $validated;
    }
}

