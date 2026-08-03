<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Authorizes via spatie/laravel-permission roles (Customer, Admin, Super Admin).
     * Falls back to the legacy `isAdmin` / `isSuperAdmin` flags so existing
     * administrators keep access until their rows are assigned spatie roles.
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Legacy flag checks run FIRST: they are pure model logic and keep
        // existing admins working even before the spatie acl_* tables exist
        // (e.g. before `php artisan migrate` is run for the first time).
        // Both the capitalized seed names and lowercase slugs are accepted.
        // The hasTable guard prevents querying acl_* tables pre-migration.
        $aclReady = Schema::hasTable('acl_roles');

        // Custom roles (created via Roles & Permissions) may hold a subset of
        // admin permissions — anyone holding ANY admin permission gets into
        // the console. Customers (seeded with zero permissions) do not.
        $hasAdminPermission = $aclReady && $user->getAllPermissions()->isNotEmpty();

        if ($role === 'super_admin') {
            if (!$user->isSuperAdmin() && !($aclReady && $user->hasAnyRole(['super_admin', 'Super Admin']))) {
                abort(403, 'Unauthorized. Super Admin access required.');
            }
        } elseif ($role === 'admin') {
            if (!$user->isAdmin() && !($aclReady && ($user->hasAnyRole(['admin', 'super_admin', 'Admin', 'Super Admin']) || $hasAdminPermission))) {
                abort(403, 'Unauthorized. Admin access required.');
            }
        } else {
            // General admin check.
            if (!$user->isAdmin() && !($aclReady && ($user->hasAnyRole(['admin', 'super_admin', 'Admin', 'Super Admin']) || $hasAdminPermission))) {
                abort(403, 'Unauthorized. Admin access required.');
            }
        }

        return $next($request);
    }
}
