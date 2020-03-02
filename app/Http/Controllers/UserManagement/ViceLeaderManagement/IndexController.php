<?php

namespace App\Http\Controllers\UserManagement\ViceLeaderManagement;

use App\Http\Controllers\Controller;
use App\Models\User;

class IndexController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewViceLeader', $user)) {
            return abort(401);
        }

        if ($user->can('editViceLeaderStudentId', $user)) {
            $canEditViceLeaderStudentId = 'true';
        } else {
            $canEditViceLeaderStudentId = 'false';
        }

        if ($user->can('editViceLeaderName', $user)) {
            $canEditViceLeaderName = 'true';
        } else {
            $canEditViceLeaderName = 'false';
        }

        if ($user->can('editViceLeaderEmail', $user)) {
            $canEditViceLeaderEmail = 'true';
        } else {
            $canEditViceLeaderEmail = 'false';
        }

        $data = [
            'canEditViceLeaderStudentId' => $canEditViceLeaderStudentId,
            'canEditViceLeaderName' => $canEditViceLeaderName,
            'canEditViceLeaderEmail' => $canEditViceLeaderEmail,
        ];

        return view('user-management.vice-leader-management.index')->with($data);
    }
}
