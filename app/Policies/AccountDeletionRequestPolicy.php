<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class AccountDeletionRequestPolicy
{
    use ChecksPermission;

    public function manage(User $user, $request = null): bool
    {
        return $this->can($user, 'manage requests');
    }
}
