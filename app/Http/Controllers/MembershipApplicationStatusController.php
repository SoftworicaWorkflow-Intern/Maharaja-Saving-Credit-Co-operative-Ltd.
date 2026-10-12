<?php

namespace App\Http\Controllers;

use App\Models\MembershipApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipApplicationStatusController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->hasPermissionTo('admin.access')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->member?->status === 'active') {
            return redirect()->route('dashboard');
        }

        $application = $user->membershipApplications()
            ->with('documents')
            ->latest('submitted_at')
            ->first();

        return view('membership.application-status', compact('application'));
    }
}
