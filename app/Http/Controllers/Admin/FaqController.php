<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(Request $request): View
    {
        $query = Faq::query();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('question_en', 'like', "%{$search}%")
                    ->orWhere('question_np', 'like', "%{$search}%")
                    ->orWhere('answer_en', 'like', "%{$search}%");
            });
        }

        $faqs = $query
            ->orderBy('display_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        return view('admin.faqs.create', [
            'faq' => new Faq(['is_active' => true, 'display_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateFaq($request);
        Faq::query()->create($validated);

        return redirect()
            ->route('admin.faqs.index')
            ->with('status', 'FAQ created successfully.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $validated = $this->validateFaq($request);
        $faq->update($validated);

        return redirect()
            ->route('admin.faqs.index')
            ->with('status', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()
            ->route('admin.faqs.index')
            ->with('status', 'FAQ deleted successfully.');
    }

    private function validateFaq(Request $request): array
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:50'],
            'question_en' => ['required', 'string', 'max:255'],
            'question_np' => ['nullable', 'string', 'max:255'],
            'answer_en' => ['required', 'string'],
            'answer_np' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['display_order'] = (int) ($validated['display_order'] ?? 0);

        return $validated;
    }
}

