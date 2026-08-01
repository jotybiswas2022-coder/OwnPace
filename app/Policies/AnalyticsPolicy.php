<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class AnalyticsPolicy
{
    use ChecksPermission;

    public function view(User $user): bool
    {
        return $this->can($user, 'view analytics');
    }

    public function export(User $user): bool
    {
        return $this->can($user, 'view analytics');
    }
}
