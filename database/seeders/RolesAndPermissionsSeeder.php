<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeds the three spatie roles the product spec defines:
 * Customer, Admin, Super Admin — plus the module permissions Admins manage.
 *
 * Tables are namespaced as acl_* (see config/permission.php) so they do not
 * collide with the legacy `roles` table this project ships with.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage products',
            'manage categories',
            'manage brands',
            'manage suppliers',
            'manage orders',
            'manage users',
            'manage requests',
            'manage campaigns',
            'manage promo codes',
            'manage content',      // sliders, faqs, terms, posts, contacts
            'manage settings',
            'view analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $customer = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        // Customers get no admin permissions by default.
        $customer->syncPermissions([]);

        // Admins manage the store but not settings or users.
        $admin->syncPermissions(array_diff($permissions, ['manage settings', 'manage users']));

        // Super Admins get everything.
        $superAdmin->syncPermissions($permissions);
    }
}
