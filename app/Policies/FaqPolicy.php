<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class FaqPolicy
{
    use ChecksPermission;

    public function manage(User $user, $faq = null): bool
    {
        return $this->can($user, 'manage content');
    }
}
