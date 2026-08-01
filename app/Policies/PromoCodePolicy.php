<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class PromoCodePolicy
{
    use ChecksPermission;

    public function manage(User $user, $promoCode = null): bool
    {
        return $this->can($user, 'manage promo codes');
    }
}
