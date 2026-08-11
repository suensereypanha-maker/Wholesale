<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of system users.
     */
    public function index(Request $request): View
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'User');
        })->with('roles')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('roles', function ($rq) use ($search) {
                      $rq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $users = $query->paginate(10)->withQueryString();

        $adminQuery = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'User');
        });

        $totalUsers = (clone $adminQuery)->count();
        $activeUsers = (clone $adminQuery)->where('status', 'active')->count();
        $pendingUsers = (clone $adminQuery)->where(function ($q) {
            $q->where('status', 'pending')->orWhereNull('status');
        })->count();
        $rejectedUsers = (clone $adminQuery)->where('status', 'rejected')->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'pendingUsers',
            'rejectedUsers'
        ));
    }

    /**
     * Display a listing of frontend registered customer users only.
     */
    public function customersRegister(Request $request): View
    {
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'User');
        })->with('roles')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('tax_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $users = $query->paginate(10)->withQueryString();

        $customerQuery = User::whereHas('roles', function ($q) {
            $q->where('name', 'User');
        });

        $totalUsers = (clone $customerQuery)->count();
        $activeUsers = (clone $customerQuery)->where('status', 'active')->count();
        $pendingUsers = (clone $customerQuery)->where(function ($q) {
            $q->where('status', 'pending')->orWhereNull('status');
        })->count();
        $rejectedUsers = (clone $customerQuery)->where('status', 'rejected')->count();

        $isCustomerRegister = true;

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'pendingUsers',
            'rejectedUsers',
            'isCustomerRegister'
        ));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'company' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'tier' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'wholesale_discount' => 'nullable|numeric|min:0|max:100',
            'role' => 'nullable|string|exists:roles,name',
            'status' => 'nullable|string|in:active,pending,rejected',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company' => $validated['company'] ?? null,
            'tax_number' => $validated['tax_number'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'province' => $validated['province'] ?? null,
            'zip' => $validated['zip'] ?? null,
            'country' => $validated['country'] ?? null,
            'tier' => $validated['tier'] ?? 'Standard Wholesale',
            'credit_limit' => $validated['credit_limit'] ?? 0.00,
            'wholesale_discount' => $validated['wholesale_discount'] ?? 0.00,
            'status' => $validated['status'] ?? 'active',
        ]);

        if (!empty($validated['role'])) {
            $user->assignRole($validated['role']);
        } else {
            $defaultRole = Role::where('name', 'User')->first();
            if ($defaultRole) {
                $user->assignRole($defaultRole->name);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', "User customer account '{$user->name}' created successfully.");
    }

    /**
     * Display the specified user profile.
     */
    public function show(User $user): View
    {
        $user->load('roles.permissions');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $user->load('roles');
        $roles = Role::orderBy('name')->get();
        $userRole = $user->roles->first()?->name;

        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'company' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'tier' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'wholesale_discount' => 'nullable|numeric|min:0|max:100',
            'role' => 'nullable|string|exists:roles,name',
            'status' => 'required|string|in:active,pending,rejected',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company' => $validated['company'] ?? null,
            'tax_number' => $validated['tax_number'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'province' => $validated['province'] ?? null,
            'zip' => $validated['zip'] ?? null,
            'country' => $validated['country'] ?? null,
            'tier' => $validated['tier'] ?? 'Standard Wholesale',
            'credit_limit' => $validated['credit_limit'] ?? 0.00,
            'wholesale_discount' => $validated['wholesale_discount'] ?? 0.00,
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        if (isset($validated['role'])) {
            $user->syncRoles($validated['role']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "User customer account '{$user->name}' updated successfully.");
    }

    /**
     * Approve a pending user account.
     */
    public function approve(User $user): RedirectResponse
    {
        $user->update(['status' => 'active']);

        return redirect()->back()
            ->with('success', "User account '{$user->name}' ({$user->email}) has been approved and activated.");
    }

    /**
     * Reject or suspend a user account.
     */
    public function reject(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot reject or suspend your own logged-in account.');
        }

        $user->update(['status' => 'rejected']);

        return redirect()->back()
            ->with('success', "User account '{$user->name}' ({$user->email}) has been rejected/suspended.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own logged-in user account.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User account '{$userName}' deleted successfully.");
    }
}
