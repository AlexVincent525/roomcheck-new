<?php

namespace App\Http\Controllers\BasicInformationManagement\RoomManagement;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Foundation\Auth\User;

class IndexController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewDormitory', Room::class)) {
            return abort(401);
        }
        return view('basic-information-management.room-management.index');
    }
}
