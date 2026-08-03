<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Roles & Permissions — Super Admin only. Manages spatie roles (acl_* tables)
 * and assigns granular permissions grouped by module.
 */
class AdminRoleController extends Controller
{
    /** Role names seeded by RolesAndPermissionsSeeder — protected from deletion. */
    protected $protectedRoles = ['customer', 'admin', 'super_admin'];

    /**
     * Permission groups shown in the editor, keyed by module label.
     */
    public static function permissionGroups(): array
    {
        return [
            'Products' => ['manage products', 'manage categories', 'manage brands', 'manage suppliers'],
            'Orders' => ['manage orders', 'manage promo codes'],
            'Customers' => ['manage users'],
            'Plans & Wallet' => ['manage plans', 'manage wallets'],
            'Broadcast' => ['manage campaigns'],
            'Reporting' => ['view dashboard', 'view analytics'],
            'Requests' => ['manage requests'],
            'Content' => ['manage content'],
            'Settings' => ['manage settings'],
        ];
    }

    public function index()
    {
        // Guard for the pre-migration state like every other acl consumer —
        // opening this screen before migrate --seed shows an empty list
        // instead of a QueryException.
        $roles = Schema::hasTable('acl_roles')
            ? SpatieRole::with('permissions')->withCount('users')->orderBy('name')->get()
            : collect();

        $allPermissions = Schema::hasTable('acl_permissions')
            ? Permission::orderBy('name')->pluck('name')->toArray()
            : [];

        return view('backend.roles.index', compact('roles', 'allPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $name = Str::title(trim($request->name));

        if (SpatieRole::where('name', $name)->where('guard_name', 'web')->exists()) {
            return back()->with('error', 'A role named "'.$name.'" already exists.')->withInput();
        }

        $role = SpatieRole::create(['name' => $name, 'guard_name' => 'web']);
        if ($request->permissions) {
            $role->syncPermissions(array_values($request->permissions));
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role "'.$name.'" created.');
    }

    public function edit(SpatieRole $role)
    {
        $role->load('permissions');

        $groups = self::permissionGroups();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('backend.roles.edit', compact('role', 'groups', 'rolePermissions'));
    }

    public function update(Request $request, SpatieRole $role)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:acl_roles,name,'.$role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        // Super Admin always holds every permission and cannot be edited.
        if (strtolower($role->name) === 'super_admin') {
            return back()->with('error', 'The Super Admin role always holds every permission and cannot be edited.');
        }

        $newName = Str::title(trim($request->name));

        // Customer/Admin names are load-bearing for the middleware + seeder.
        if ($this->isProtected($role->name) && strtolower($newName) !== strtolower($role->name)) {
            return back()->with('error', 'Seeded role names (Customer, Admin) are load-bearing and cannot be renamed.');
        }

        $role->update(['name' => $newName]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role "'.$newName.'" updated.');
    }

    public function destroy(SpatieRole $role)
    {
        if ($this->isProtected($role->name)) {
            return back()->with('error', 'Seeded roles (Customer, Admin, Super Admin) cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Assign users off this role before deleting it.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted.');
    }

    protected function isProtected(string $name): bool
    {
        return in_array(strtolower($name), $this->protectedRoles, true);
    }
}
