<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class BrandPolicy
{
    use ChecksPermission;

    public function manage(User $user, $brand = null): bool
    {
        return $this->can($user, 'manage brands');
    }
}
