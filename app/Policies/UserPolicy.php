<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    public function viewMembers(User $user)
    {
        return $this->checkPermission($user,'viewMembers');
    }

    public function addMember(User $user)
    {
        return $this->checkPermission($user, 'addMember');
    }

    public function activeMember(User $user)
    {
        return $this->checkPermission($user,'activeMember');
    }

    public function deleteMember(User $user)
    {
        return $this->checkPermission($user, 'deleteMember');
    }

    public function importMember(User $user)
    {
        return $this->checkPermission($user, 'importMember');
    }

    public function editMemberName(User $user)
    {
        return $this->checkPermission($user,'editMemberName');
    }

    public function editMemberEmail(User $user)
    {
        return $this->checkPermission($user, 'editMemberEmail');
    }

    public function editMemberStudentId(User $user)
    {
        return $this->checkPermission($user, 'editMembersStudentId');
    }

    public function viewViceLeader(User $user)
    {
        return $this->checkPermission($user, 'viewViceLeader');
    }

    public function editViceLeaderStudentId(User $user)
    {
        return $this->checkPermission($user, 'editViceLeaderStudentId');
    }

    public function editViceLeaderName(User $user)
    {
        return $this->checkPermission($user, 'editViceLeaderName');
    }

    public function editViceLeaderEmail(User $user)
    {
        return $this->checkPermission($user, 'editViceLeaderEmail');
    }

    public function addViceLeader(User $user)
    {
        return $this->checkPermission($user, 'addViceLeader');
    }

    public function deleteViceLeader(User $user)
    {
        return $this->checkPermission($user, 'deleteViceLeader');
    }

    public function activeViceLeader(User $user)
    {
        return $this->checkPermission($user, 'activeViceLeader');
    }
}
