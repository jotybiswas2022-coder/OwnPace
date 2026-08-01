<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }
        if ($request->role_id) {
            $query->where('role_id', $request->role_id);
        }
        if ($request->status === 'suspended') {
            $query->where('is_suspended', true);
        } elseif ($request->status === 'active') {
            $query->where('is_active', true)->where('is_suspended', false);
        }

        $users = $query->latest()->paginate(20);
        $roles = Role::all();

        return view('backend.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $user->load(['orders' => function($q) { $q->latest()->take(10); }, 'wallet']);
        return view('backend.users.show', compact('user'));
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user)
    {
        $this->authorize('manage', $user);

        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'You cannot change a Super Admin role.');
        }

        $user->update(['role_id' => $request->role_id]);
        return back()->with('success', 'User role updated successfully!');
    }

    public function suspend(User $user)
    {
        $this->authorize('manage', $user);

        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'You cannot suspend a Super Admin.');
        }

        $user->update([
            'is_suspended' => true,
            'suspended_at' => now(),
        ]);

        return back()->with('success', 'User suspended successfully!');
    }

    public function unsuspend(User $user)
    {
        $this->authorize('manage', $user);

        $user->update([
            'is_suspended' => false,
            'suspended_at' => null,
        ]);

        return back()->with('success', 'User unsuspended successfully!');
    }

    public function destroy(User $user)
    {
        $this->authorize('manage', $user);

        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'You cannot delete a Super Admin.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }


}
