<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class SliderPolicy
{
    use ChecksPermission;

    public function manage(User $user, $slider = null): bool
    {
        return $this->can($user, 'manage content');
    }
}
