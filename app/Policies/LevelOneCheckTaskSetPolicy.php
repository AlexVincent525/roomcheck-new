<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class LevelOneCheckTaskSetPolicy
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

    public function viewLevelOneCheckSet(User $user)
    {
        return $this->checkPermission($user,'viewLevelOneCheckSet');
    }

    public function addLevelOneCheckSet(User $user)
    {
        return $this->checkPermission($user,'addLevelOneCheckSet');
    }

    public function deleteLevelOneCheckSet(User $user)
    {
        return $this->checkPermission($user,'deleteLevelOneCheckSet');
    }

}
