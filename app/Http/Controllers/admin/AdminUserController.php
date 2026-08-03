<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\User;
use App\Models\Role;
use App\Models\Notification;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')->withCount('orders');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->role) {
            // Filter by spatie role name when acl is live, else legacy role_id.
            if (Schema::hasTable('acl_roles')) {
                $query->role($request->role);
            } elseif ($request->role === 'admin') {
                $query->where('is_admin', true);
            } else {
                $query->where('is_admin', false);
            }
        }

        if ($request->status === 'suspended') {
            $query->where('is_suspended', true);
        } elseif ($request->status === 'active') {
            $query->where('is_active', true)->where('is_suspended', false);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($request->verification) {
            $query->where('identity_verification', $request->verification);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        $roles = Schema::hasTable('acl_roles') ? SpatieRole::orderBy('name')->get() : collect();
        $legacyRoles = Role::all();

        return view('backend.users.index', compact('users', 'roles', 'legacyRoles'));
    }

    public function show(User $user)
    {
        $user->load([
            'orders' => fn ($q) => $q->with(['installmentPlan', 'installmentPayments'])->latest()->take(10),
            'wallet',
            'verifications',
            'savedCards',
            'bankAccounts',
            'planChangeRequests',
            'exchangeRequests',
            'productRequests',
        ]);

        $overdueCount = $user->orders()
            ->whereHas('installmentPayments', fn ($q) => $q->where('status', 'overdue'))
            ->count();

        $nextDue = $user->orders()
            ->whereHas('installmentPayments', fn ($q) => $q->where('status', 'pending')->whereDate('due_date', '>=', now()->toDateString()))
            ->with(['installmentPayments' => fn ($q) => $q->where('status', 'pending')->orderBy('due_date')])
            ->get()
            ->flatMap(fn ($o) => $o->installmentPayments)
            ->sortBy('due_date')
            ->first();

        $roles = Schema::hasTable('acl_roles') ? SpatieRole::orderBy('name')->get() : collect();

        return view('backend.users.show', compact('user', 'overdueCount', 'nextDue', 'roles'));
    }

    /**
     * Assign a spatie role (the modern roles & permissions system).
     */
    public function assignRole(Request $request, User $user)
    {
        $this->authorize('manage', $user);

        $request->validate([
            'role_id' => 'required|integer',
        ]);

        if (!Schema::hasTable('acl_roles')) {
            return back()->with('error', 'Run php artisan migrate --seed first to enable roles & permissions.');
        }

        $role = SpatieRole::find($request->role_id);
        if (!$role) {
            return back()->with('error', 'Role not found.');
        }

        // Super Admin role assignment / changes are Super Admin only.
        $isSuperAdminRole = in_array(strtolower($role->name), ['super_admin', 'super admin']);
        if (!auth()->user()->isSuperAdmin() && ($isSuperAdminRole || $user->hasAnyRole(['super_admin', 'Super Admin']))) {
            return back()->with('error', 'Only a Super Admin can assign or change Super Admin roles.');
        }

        $user->syncRoles([$role->name]);

        return back()->with('success', 'Role set to "'.$role->name.'".');
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

    /**
     * Permanent delete — Super Admin only, enforced by UserPolicy.
     */
    public function permanentDelete(User $user)
    {
        $this->authorize('permanentlyDelete', $user);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->forceDelete();

        return redirect()->route('admin.users.index')->with('success', 'User permanently deleted.');
    }

    /**
     * Send an individual payment reminder for the user's overdue / due-soon
     * installments. Creates in-app notifications (the same channel Campaigns use).
     */
    public function sendReminder(User $user)
    {
        $this->authorize('manage', $user);

        $duePayments = $user->orders()
            ->whereHas('installmentPayments', fn ($q) => $q->whereIn('status', ['pending', 'overdue']))
            ->with(['installmentPayments' => fn ($q) => $q->whereIn('status', ['pending', 'overdue'])->orderBy('due_date')])
            ->get()
            ->flatMap(fn ($o) => $o->installmentPayments)
            ->filter(fn ($p) => $p->status === 'overdue' || $p->due_date->lte(now()->addDays(3)))
            ->values();

        if ($duePayments->isEmpty()) {
            return back()->with('error', 'This customer has no overdue or due-soon installments.');
        }

        $count = 0;
        foreach ($duePayments as $payment) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'payment_reminder',
                'channel' => 'email',
                'title' => 'Payment reminder — installment #'.$payment->installment_number,
                'message' => 'Your installment of '.formatPrice($payment->amount, 0)
                    .' for order #'.$payment->order?->order_number
                    .' was due on '.$payment->due_date->format('M j, Y')
                    .'. Please pay to keep your plan on track.',
                'status' => 'pending',
            ]);
            $count++;
        }

        return back()->with('success', 'Payment reminder sent to '.$user->name.' ('.$count.' installment(s)).');
    }

    /**
     * Save internal support notes visible only to staff.
     */
    public function updateSupportNotes(Request $request, User $user)
    {
        $this->authorize('manage', $user);

        $request->validate([
            'support_notes' => 'nullable|string|max:5000',
        ]);

        $user->update(['support_notes' => $request->support_notes]);

        return back()->with('success', 'Support notes saved.');
    }
}
