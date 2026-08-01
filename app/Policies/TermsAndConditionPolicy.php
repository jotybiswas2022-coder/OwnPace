<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class TermsAndConditionPolicy
{
    use ChecksPermission;

    public function manage(User $user, $term = null): bool
    {
        return $this->can($user, 'manage content');
    }
}
