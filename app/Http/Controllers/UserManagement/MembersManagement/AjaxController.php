<?php

namespace App\Http\Controllers\UserManagement\MembersManagement;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Other\DataTemplate;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Maatwebsite\Excel\Facades\Excel;

class AjaxController extends Controller
{
    public function userList(RoleRepository $roleRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewMembers', $user)) {
            return abort(401);
        }

        $request = request();

        /** @var Role $memberRole */
        $memberRole = $roleRepository->findByField('english_name', 'member')->first();
        $members = $memberRole->users;
        $total = $members->count();

        $limit = $request->input('limit');
        $page = $request->input('page');
        $members = $members->forPage($page, $limit);

        /** @var DataTemplate $data */
        $data = app(DataTemplate::class);
        $data->total = $total;
        $data->rows = [];

        $userTemp = new class {
            public $id;
            public $name;
            public $student_id;
            public $email;
            public $active;
        };

        foreach ($members as $member) {
            /** @var User $member */
            $user = clone $userTemp;
            $user->id = $member->id;
            $user->name = $member->name;
            $user->student_id = $member->student_id;
            $user->email = $member->email;
            $user->active = $member->is_alive;

            $data->rows[] = $user;
        }

        return response()->json($data);
    }

    public function editUserProfile(
        UserRepository $userRepository,
        RoleRepository $roleRepository
    )
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewMembers', $user)) {
            return abort(401);
        }

        $request = request();
        $userId = $request->input('id');
        $field = $request->input('field');
        $value = $request->input('value');

        /** @var User $modifiedUser */
        $modifiedUser = $userRepository->find($userId);
        $memberRoleId = $roleRepository->findByField('english_name', 'member')->first()->id;
        if ($modifiedUser->role_id != $memberRoleId) {
            return abort(403);
        }

        if ($field == 'name') {
            if ($user->cannot('editMemberName', $user)) {
                return abort(403);
            }
        }

        if ($field == 'student_id') {
            if ($user->cannot('editMemberStudentId', $user)) {
                return abort(403);
            }
        }

        if ($field == 'email') {
            if ($user->cannot('editMemberEmail', $user)) {
                return abort(403);
            }
        }

        $modifiedUser->$field = $value;
        $modifiedUser->save();

        $data = app(DataTemplate::class);
        return response()->json($data);
    }

    public function addMember(RoleRepository $roleRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('addMember', $user)) {
            return abort(403);
        }

        $request = request();
        $name = $request->input('name');
        $studentId = $request->input('student_id');
        $email = $request->input('email');

        /** @var User $newUser */
        $newUser = app(User::class);
        $newUser->name = $name;
        $newUser->student_id = $studentId;
        $newUser->email = $email;
        $newUser->password = bcrypt('zilvhui#2018');

        /** @var Role $memberRole */
        $memberRole = $roleRepository->findByField('english_name', 'member')->first();
        $newUser->role_id = $memberRole->id;
        $newUser->save();

        $data = app(DataTemplate::class);
        return response()->json($data);
    }

    public function changeMemberStatus(
        UserRepository $userRepository,
        RoleRepository $roleRepository
    )
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('activeMember', $user)) {
            return abort(403);
        }

        $request = request();
        $userIdArray = $request->input('users');
        $memberRoleId = $roleRepository->findByField('english_name', 'member')->first()->id;
        foreach ($userIdArray as $userId) {
            /** @var User $modifiedUser */
            $modifiedUser = $userRepository->find($userId);
            if ($modifiedUser->role_id != $memberRoleId) {
                return abort(403);
            }
            if ($modifiedUser->is_alive) {
                $modifiedUser->is_alive = false;
            } else {
                $modifiedUser->is_alive = true;
            }

            $modifiedUser->save();
        }

        $data = app(DataTemplate::class);
        return response()->json($data);
    }

    public function importUser(RoleRepository $roleRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('importMember', $user)) {
            return abort(403);
        }

        $request = request();
        $file = $request->file('file_data');
        $reader = Excel::load($file);
        $memberRoleId = $roleRepository->findByField('english_name', 'member')->first()->id;
        $reader->each(function ($row) use ($memberRoleId) {
            if ($row->name != null) {
                /** @var User $newUser */
                $newUser = app(User::class);
                $newUser->student_id = $row->student_id;
                $newUser->name = $row->name;
                $newUser->email = $row->qq . '@qq.com';
                $newUser->password = bcrypt('zilvhui#2018');
                $newUser->role_id = $memberRoleId;
                $newUser->save();
            }
        });
        $data = app(DataTemplate::class);
        return response()->json($data);
    }
}
