<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class ProductPolicy
{
    use ChecksPermission;

    public function manage(User $user, $product = null): bool
    {
        return $this->can($user, 'manage products');
    }
}
