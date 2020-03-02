<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class LevelTwoCheckTaskSetPolicy
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

    public function viewLevelTwoCheckSet(User $user)
    {
        return $this->checkPermission($user,'viewLevelTwoCheckSet');
    }

    public function addLevelTwoCheckSet(User $user)
    {
        return $this->checkPermission($user,'addLevelTwoCheckSet');
    }

    public function deleteLevelTwoCheckSet(User $user)
    {
        return $this->checkPermission($user,'deleteLevelTwoCheckSet');
    }
}
