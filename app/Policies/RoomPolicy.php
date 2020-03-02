<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomPolicy
{
    use HandlesAuthorization;

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

    public function viewDormitory(User $user)
    {
        return $this->checkPermission($user,'viewDormitory');
    }

    public function editDormitory(User $user)
    {
        return $this->checkPermission($user,'editDormitory');
    }

    public function addDormitory(User $user)
    {
        return $this->checkPermission($user,'addDormitory');
    }

    public function deleteDormitory(User $user)
    {
        return $this->checkPermission($user,'deleteDormitory');
    }

    public function activeDormitory(User $user)
    {
        return $this->checkPermission($user,'activeDormitory');
    }

    public function importDormitory(User $user)
    {
        return $this->checkPermission($user,'importDormitory');
    }
}
