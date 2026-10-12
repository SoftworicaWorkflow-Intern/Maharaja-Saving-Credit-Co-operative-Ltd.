<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembershipApplicationRequest;
use App\Models\MembershipApplication;
use App\Services\MembershipApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if ($request->user()->hasPermissionTo('admin.access')) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($request->user()->member?->status !== 'active') {
                return redirect()->intended(route('membership.application'));
            }

            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('These credentials do not match our records.'),
        ]);
    }

    public function registerForm(): View
    {
        return view('auth.register');
    }

    public function register(
        StoreMembershipApplicationRequest $request,
        MembershipApplicationService $applications,
    )
    {
        [$user] = $applications->submit(
            $request->safe()->only(['name', 'email', 'phone', 'address', 'password']),
            $request->file('identity_document'),
        );

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('membership.application');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
