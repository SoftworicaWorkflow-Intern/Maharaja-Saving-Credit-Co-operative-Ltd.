<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->whereKeyNot($request->user()->getKey())
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'is_admin']);

        return view('admin.settings.index', compact('users'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('status', __('Your administrator password has been updated.'));
    }

    public function updateUserPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ]);

        $user = User::query()->findOrFail($validated['user_id']);

        if ($user->is($request->user())) {
            throw ValidationException::withMessages([
                'user_id' => __('Use the administrator password form to change your own password.'),
            ]);
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('status', __('The selected user password has been updated.'));
    }
}
