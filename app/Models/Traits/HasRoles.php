<?php

namespace App\Models\Traits;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Assign role(s) to the user.
     */
    public function assignRole(...$roles): self
    {
        $roleIds = collect($roles)
            ->flatten()
            ->map(function ($role) {
                if (is_string($role)) {
                    return Role::where('name', $role)->firstOrFail()->id;
                }
                return $role instanceof Role ? $role->id : $role;
            });

        $this->roles()->syncWithoutDetaching($roleIds);

        return $this;
    }

    /**
     * Remove role from user.
     */
    public function removeRole($role): self
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role->id);
        }

        return $this;
    }

    /**
     * Sync roles for user.
     */
    public function syncRoles(...$roles): self
    {
        $roleIds = collect($roles)
            ->flatten()
            ->map(function ($role) {
                if (is_string($role)) {
                    return Role::where('name', $role)->firstOrFail()->id;
                }
                return $role instanceof Role ? $role->id : $role;
            });

        $this->roles()->sync($roleIds);

        return $this;
    }

    /**
     * Check if user has a role.
     */
    public function hasRole(string|array|Role $roles): bool
    {
        if (is_string($roles)) {
            return $this->roles->contains('name', $roles);
        }

        if ($roles instanceof Role) {
            return $this->roles->contains('id', $roles->id);
        }

        if (is_array($roles)) {
            return $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
        }

        return false;
    }

    /**
     * Check if user has permission through any assigned role.
     */
    public function hasPermissionTo(string|Permission $permission): bool
    {
        $permissionName = $permission instanceof Permission ? $permission->name : $permission;

        foreach ($this->roles as $role) {
            if ($role->hasPermissionTo($permissionName)) {
                return true;
            }
        }

        return false;
    }
}
