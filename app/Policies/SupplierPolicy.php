<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermission;

class SupplierPolicy
{
    use ChecksPermission;

    /**
     * Suppliers are admin-only (never exposed on customer routes); the
     * `manage suppliers` permission gates every admin supplier action.
     */
    public function manage(User $user, $supplier = null): bool
    {
        return $this->can($user, 'manage suppliers');
    }
}
