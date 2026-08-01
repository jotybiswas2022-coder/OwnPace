<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class SettingPolicy
{
    use ChecksPermission;

    public function manage(User $user): bool
    {
        return $this->can($user, 'manage settings');
    }
}
