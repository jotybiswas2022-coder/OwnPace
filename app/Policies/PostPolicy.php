<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class PostPolicy
{
    use ChecksPermission;

    public function manage(User $user, $post = null): bool
    {
        return $this->can($user, 'manage content');
    }
}
