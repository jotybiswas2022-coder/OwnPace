<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;
use Illuminate\Support\Facades\Schema;

class UserPolicy
{
    use ChecksPermission;

    public function manage(User $user, $target = null): bool
    {
        return $this->can($user, 'manage users');
    }

    /**
     * Permanent deletion is Super Admin only — enforced here in the policy,
     * not just by hiding the button in the UI.
     */
    public function permanentlyDelete(User $user, $target = null): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return Schema::hasTable('acl_roles')
            && $user->hasAnyRole(['super_admin', 'Super Admin']);
    }
}
