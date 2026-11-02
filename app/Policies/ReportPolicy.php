<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('reports.manage');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('reports.manage');
    }

    public function update(User $user, Report $report): bool
    {
        return $user->hasPermissionTo('reports.manage');
    }

    public function delete(User $user, Report $report): bool
    {
        return $user->hasPermissionTo('reports.manage');
    }
}
