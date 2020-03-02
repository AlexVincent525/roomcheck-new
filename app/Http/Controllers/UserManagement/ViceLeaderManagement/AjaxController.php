<?php

namespace App\Http\Controllers\UserManagement\ViceLeaderManagement;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Other\DataTemplate;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;

class AjaxController extends Controller
{
    public function ViceLeaderList(RoleRepository $roleRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewViceLeader', $user)) {
            return abort(401);
        }

        $request = request();

        /** @var Role $viceLeaderRole */
        $viceLeaderRole = $roleRepository->findByField('english_name', 'viceLeader')->first();
        $viceLeaders = $viceLeaderRole->users;
        $total = $viceLeaders->count();

        $limit = $request->input('limit');
        $page = $request->input('page');
        $viceLeaders = $viceLeaders->forPage($page, $limit);

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

        foreach ($viceLeaders as $viceLeader) {
            /** @var User $viceLeader */
            $user = clone $userTemp;
            $user->id = $viceLeader->id;
            $user->name = $viceLeader->name;
            $user->student_id = $viceLeader->student_id;
            $user->email = $viceLeader->email;
            if ($viceLeader->is_alive) {
                $user->active = true;
            } else {
                $user->active = false;
            }
            $data->rows[] = $user;
        }

        return response()->json($data);
    }

    public function addViceLeader(RoleRepository $roleRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('addViceLeader', $user)) {
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
        $memberRole = $roleRepository->findByField('english_name', 'viceLeader')->first();
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
        if ($user->cannot('activeViceLeader', $user)) {
            return abort(403);
        }

        $request = request();
        $userIdArray = $request->input('users');
        $memberRoleId = $roleRepository->findByField('english_name', 'viceLeader')->first()->id;
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
}