<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LeadershipProfileController extends Controller
{
    public function index(): View
    {
        $profiles = config('leadership.messages');

        foreach ($profiles as $leader => $profile) {
            $profiles[$leader]['name'] = Setting::value($profile['name_setting']);
            $portraitPath = Setting::value($profile['portrait_setting']);
            $profiles[$leader]['has_portrait'] = is_string($portraitPath)
                && Storage::disk('local')->exists($portraitPath);
        }

        return view('admin.leadership.index', compact('profiles'));
    }

    public function update(Request $request, string $leader): RedirectResponse
    {
        $profiles = config('leadership.messages');
        abort_unless(array_key_exists($leader, $profiles), 404);

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'portrait' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $profile = $profiles[$leader];
        $name = filled($validated['name'] ?? null) ? trim($validated['name']) : null;
        $oldPortraitPath = null;
        $portraitPath = null;

        if ($request->hasFile('portrait')) {
            $oldPortraitPath = Setting::value($profile['portrait_setting']);
            $portraitPath = $request->file('portrait')->store('leadership', 'local');

            if (! is_string($portraitPath)) {
                throw new \RuntimeException('The uploaded leadership portrait could not be stored.');
            }
        }

        Setting::updateOrCreate(['key' => $profile['name_setting']], ['value' => $name]);

        if (is_string($portraitPath)) {
            Setting::updateOrCreate(
                ['key' => $profile['portrait_setting']],
                ['value' => $portraitPath],
            );

            if (is_string($oldPortraitPath) && $oldPortraitPath !== $portraitPath) {
                Storage::disk('local')->delete($oldPortraitPath);
            }
        }

        return redirect()
            ->route('admin.leadership.index')
            ->with('status', "{$profile['designation']} profile updated.");
    }
}
