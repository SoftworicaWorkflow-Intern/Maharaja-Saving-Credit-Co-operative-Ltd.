<?php

namespace App\Http\Requests;

use App\Models\Notice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveNoticeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('notices.manage') === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title_np' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'body_np' => ['nullable', 'string'],
            'body_en' => ['nullable', 'string'],
            'category' => ['required', Rule::in(Notice::CATEGORIES)],
            'status' => ['required', Rule::in(Notice::STATUSES)],
            'published_at' => ['nullable', 'date'],
            'featured' => ['sometimes', 'boolean'],
        ];
    }
}
