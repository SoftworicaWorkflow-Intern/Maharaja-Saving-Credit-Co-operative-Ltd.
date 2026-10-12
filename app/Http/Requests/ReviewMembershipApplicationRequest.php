<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewMembershipApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermissionTo('applications.review') ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'note' => $this->route('decision') === 'approve'
                ? ['nullable', 'string', 'max:2000']
                : ['required', 'string', 'max:2000'],
        ];
    }
}
