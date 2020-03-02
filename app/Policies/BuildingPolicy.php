<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class BuildingPolicy
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

    public function viewBuilding(User $user)
    {
        return $this->checkPermission($user,'viewBuilding');
    }

    public function editBuilding(User $user)
    {
        return $this->checkPermission($user,'editBuilding');
    }

    public function addBuilding(User $user)
    {
        return $this->checkPermission($user,'addBuilding');
    }

    public function deleteBuilding(User $user)
    {
        return $this->checkPermission($user,'deleteBuilding');
    }

    public function activeBuilding(User $user)
    {
        return $this->checkPermission($user,'deleteBuilding');
    }
}
