<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class DashboardPolicy
{
    use ChecksPermission;

    public function view(User $user): bool
    {
        return $this->can($user, 'view dashboard');
    }
}
