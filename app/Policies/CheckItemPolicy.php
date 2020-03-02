<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class CheckItemPolicy
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

    public function viewItem(User $user)
    {
        return $this->checkPermission($user,'viewItem');
    }

    public function editItem(User $user)
    {
        return $this->checkPermission($user,'editItem');
    }

    public function addItem(User $user)
    {
        return $this->checkPermission($user,'addItem');
    }

    public function deleteItem(User $user)
    {
        return $this->checkPermission($user,'deleteItem');
    }
}
