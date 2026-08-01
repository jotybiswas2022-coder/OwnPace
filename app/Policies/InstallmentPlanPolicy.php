<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class InstallmentPlanPolicy
{
    use ChecksPermission;

    public function manage(User $user, $plan = null): bool
    {
        return $this->can($user, 'manage plans');
    }
}
