<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class WalletPolicy
{
    use ChecksPermission;

    public function manage(User $user, $wallet = null): bool
    {
        return $this->can($user, 'manage wallets');
    }
}
