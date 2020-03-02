<?php

namespace App\Http\Controllers\UserManagement\ChangeProfile;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('user-management.change-profile.index');
    }
}
