<?php

namespace App\Policies\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Schema;

/**
 * Shared permission check for admin policies.
 *
 * Before the spatie acl_* tables exist (pre-migration), the legacy `isAdmin()`
 * flag is the source of truth — and we never query a table that doesn't exist.
 * Once the tables exist, granular spatie permissions are authoritative; the
 * legacy flag remains an escape hatch so admins created before spatie roles
 * were assigned keep working (matching AdminMiddleware's behavior).
 */
trait ChecksPermission
{
    protected function can(User $user, string $permission): bool
    {
        if (! Schema::hasTable('acl_permissions')) {
            return $user->isAdmin();
        }

        return $user->isAdmin() || $user->hasPermissionTo($permission);
    }
}
