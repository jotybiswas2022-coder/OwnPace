<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class ProductFeePolicy
{
    use ChecksPermission;

    public function manage(User $user, $fee = null): bool
    {
        return $this->can($user, 'manage orders');
    }
}
