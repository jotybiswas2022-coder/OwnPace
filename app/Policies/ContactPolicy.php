<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class ContactPolicy
{
    use ChecksPermission;

    public function view(User $user): bool
    {
        return $this->can($user, 'manage content');
    }
}
