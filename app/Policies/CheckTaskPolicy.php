<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class CheckTaskPolicy
{
    protected function checkPermission(User $user, $permissionEnglishName)
    {
        $permissions = $user->role->permissions;
        $result = $permissions->search(function ($permission) use ($permissionEnglishName) {
            /** @var Permission $permission */
            return $permission->english_name == $permissionEnglishName;
        });
        if ($result === false) {
            return false;
        } else {
            return true;
        }
    }

    public function viewCheckTask(User $user)
    {
        return $this->checkPermission($user,'viewCheckTask');
    }

    public function editCheckTask(User $user)
    {
        return $this->checkPermission($user,'editCheckTask');
    }

    public function addCheckTask(User $user)
    {
        return $this->checkPermission($user,'addCheckTask');
    }

    public function deleteCheckTask(User $user)
    {
        return $this->checkPermission($user, 'deleteCheckTask');
    }

    public function changeStateOfCheckTask(User $user)
    {
        return $this->checkPermission($user, 'changeStateOfCheckTask');
    }
}
