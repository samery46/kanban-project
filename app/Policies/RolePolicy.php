<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;

class RolePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    protected function getUserPermissions($user)
    {
        return $user
            ->role()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name');
    }

    public function before($user)
    {
        if ($user->role && $user->role->name == 'admin') {
            return true;
        }

        return null;
    }

    public function viewAllRoles(User $user): bool
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('view-all-role')) {
            return true;
        }

        return false;
    }

    public function manageRoles(User $user): bool
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('manage-roles')) {
            return true;
        }

        return false;
    }
}
