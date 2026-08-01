<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class OrderPolicy
{
    use ChecksPermission;

    public function manage(User $user, $order = null): bool
    {
        return $this->can($user, 'manage orders');
    }
}
