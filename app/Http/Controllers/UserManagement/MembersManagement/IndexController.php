<?php

namespace App\Http\Controllers\UserManagement\MembersManagement;

use App\Http\Controllers\Controller;
use App\Models\User;

class IndexController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewMembers', $user)) {
            return abort(401);
        }

        if ($user->can('editMemberName', $user)) {
            $canEditMemberName = 'true';
        } else {
            $canEditMemberName = 'false';
        }
        if ($user->can('editMemberStudentId', $user)) {
            $canEditMemberStudentId = 'true';
        } else {
            $canEditMemberStudentId = 'false';
        }
        if ($user->can('editMemberEmail', $user)) {
            $canEditMemberEmail = 'true';
        } else {
            $canEditMemberEmail = 'false';
        }

        $data = [
            'canEditMemberName' => $canEditMemberName,
            'canEditMemberStudentId' => $canEditMemberStudentId,
            'canEditMemberEmail' => $canEditMemberEmail
        ];

        return view('user-management.members-management.index')->with($data);
    }
}
