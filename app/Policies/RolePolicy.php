<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function update($user, $permission): bool
    {
        return $user->id == $permission->user_id;
    }

    public function delete($user, $permission): bool
    {
        return $user->id == $permission->user_id;
    }

    protected function getUserPermissions($user)
    {
        return $user->role()
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

    public function createNewRoles(user $user): bool
    {
        $permissions = $this->getUserPermissions($user);
        if ($permissions->contains('create-new-roles')) {
            return true;
        }
        return false;
    }

    public function updateAnyRoles(User $user): bool
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('update-any-roles')) {
            return true;
        }

        return false;
    }


    public function deleteAnyRoles(User $user): bool
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('delete-any-roles')) {
            return true;
        }

        return false;
    }

    public function viewAnyRoles(User $user): bool
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('view-any-roles')) {
            return true;
        }

        return false;
    }



    public function manageUserRoles(User $user): bool
    {
        $permissions = $this->getUserPermissions($user);

        if ($permissions->contains('manage-user-roles')) {
            return true;
        }

        return false;
    }
}
