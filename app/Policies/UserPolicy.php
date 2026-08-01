<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class UserPolicy
{
    use ChecksPermission;

    public function manage(User $user, $target = null): bool
    {
        return $this->can($user, 'manage users');
    }
}
