<?php

use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function getPermissions($permissionNames)
    {
        /** @var \App\Repositories\PermissionRepository $permissionRepository */
        $permissions = [];
        $permissionRepository = app(\App\Repositories\PermissionRepository::class);
        foreach ($permissionNames as $permissionName) {
            $permission = $permissionRepository->findWhere(['english_name' => $permissionName])->first();
            $permissions[] = $permission;
        }

        return $permissions;
    }

    public function data() : array
    {
        return [
            'member' => [
                'viewLevelOneCheckSet',
                'viewLevelTwoCheckSet',
                'viewCheckTask',
                'editCheckTask',
                'changeStateOfCheckTask',
                'viewItem',
                'viewSelf',
                'editSelfName',
                'viewAnnouncement',
                'viewTimeline',
            ],

            'viceLeader' => [
                'viewLevelOneCheckSet',
                'addLevelOneCheckSet',
                'deleteLevelOneCheckSet',

                'viewLevelTwoCheckSet',
                'addLevelTwoCheckSet',
                'deleteLevelTwoCheckSet',

                'viewCheckTask',
                'addCheckTask',
                'deleteCheckTask',
                'changeStateOfCheckTask',

                'viewBuilding',

                'viewDormitory',

                'viewItem',

                'viewSelf',
                'editSelfStudentId',
                'editSelfName',
                'editSelfEmail',

                'viewMembers',
                'editMembersStudentId',
                'editMemberName',
                'editMemberEmail',
                'addMember',
                'deleteMember',
                'activeMember',
                'importMember',

                'viewViceLeader',

                'viewAnnouncement',
                'editAnnouncement',

                'viewTimeline'
            ],

            'leader' => [
                'viewLevelOneCheckSet',
                'addLevelOneCheckSet',
                'deleteLevelOneCheckSet',

                'viewLevelTwoCheckSet',
                'addLevelTwoCheckSet',
                'deleteLevelTwoCheckSet',

                'viewCheckTask',
                'editCheckTask',
                'addCheckTask',
                'deleteCheckTask',
                'changeStateOfCheckTask',

                'viewBuilding',
                'editBuilding',
                'addBuilding',
                'deleteBuilding',
                'activeBuilding',

                'viewDormitory',
                'editDormitory',
                'addDormitory',
                'deleteDormitory',
                'activeDormitory',
                'importDormitory',

                'viewItem',
                'editItem',
                'addItem',
                'deleteItem',

                'viewSelf',
                'editSelfStudentId',
                'editSelfName',
                'editSelfEmail',

                'viewMembers',
                'editMembersStudentId',
                'editMemberName',
                'editMemberEmail',
                'addMember',
                'deleteMember',
                'activeMember',
                'importMember',

                'viewViceLeader',
                'editViceLeaderStudentId',
                'editViceLeaderName',
                'editViceLeaderEmail',
                'addViceLeader',
                'deleteViceLeader',
                'activeViceLeader',

                'viewLeader',
                'editLeaderStudentId',
                'editLeaderName',
                'editLeaderEmail',

                'viewAnnouncement',
                'editAnnouncement',

                'viewTimeline',
            ],

            'sysadmin' => 'all',
        ];
    }

    public function run(
        \App\Repositories\RoleRepository $roleRepository,
        \App\Repositories\PermissionRepository $permissionRepository
    )
    {
        foreach ($this->data() as $roleName => $permissionsNames) {
            /** @var \App\Models\Role $role */
            $role = $roleRepository->findWhere(['english_name' => $roleName])->first();
            if ($permissionsNames == 'all') {
                $permissions = $permissionRepository->all();
                $role->permissions()->saveMany($permissions);
            } else {
                $permissions = $this->getPermissions($permissionsNames);
                $role->permissions()->saveMany($permissions);
            }
        }
    }
}
