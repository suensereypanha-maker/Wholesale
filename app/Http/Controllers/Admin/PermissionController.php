<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Display a listing of system permissions.
     */
    public function index(Request $request): View
    {
        $query = Permission::with('roles')->withCount('roles');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $permissions = $query->latest()->get();

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create(): View
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string|max:1000',
        ]);

        // Normalize permission name slug (snake_case)
        $permName = Str::slug(Str::lower($validated['name']), '_');

        $permission = Permission::create([
            'name' => $permName,
            'guard_name' => 'web',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission '{$permission->name}' created successfully.");
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission): View
    {
        $permission->load(['roles.users']);

        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            'description' => 'nullable|string|max:1000',
        ]);

        $permName = Str::slug(Str::lower($validated['name']), '_');

        $permission->update([
            'name' => $permName,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission '{$permission->name}' updated successfully.");
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        // Guard critical system permissions
        $protectedPermissions = [
            'view_dashboard',
            'manage_users',
            'manage_roles',
            'manage_orders',
            'manage_products',
            'view_reports',
        ];

        if (in_array($permission->name, $protectedPermissions)) {
            return back()->with('error', "Core system permission '{$permission->name}' cannot be deleted.");
        }

        $permName = $permission->name;
        $permission->roles()->detach();
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission '{$permName}' deleted successfully.");
    }
}
