<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactInquiryRequest;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;

class ContactInquiryController extends Controller
{
    public function store(StoreContactInquiryRequest $request): RedirectResponse
    {
        ContactInquiry::query()->create($request->validated());

        return redirect()
            ->route('contact')
            ->with('status', __('Thank you. Your message has been sent to the cooperative.'));
    }
}
