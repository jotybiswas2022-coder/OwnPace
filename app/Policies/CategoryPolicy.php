<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class CategoryPolicy
{
    use ChecksPermission;

    public function manage(User $user, $category = null): bool
    {
        return $this->can($user, 'manage categories');
    }
}
