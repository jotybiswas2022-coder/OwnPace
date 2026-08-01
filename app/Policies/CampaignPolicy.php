<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class CampaignPolicy
{
    use ChecksPermission;

    public function manage(User $user, $campaign = null): bool
    {
        return $this->can($user, 'manage campaigns');
    }
}
