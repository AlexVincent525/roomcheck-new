<?php

namespace App\Http\Controllers\BasicInformationManagement\BuildingManagement;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\User;

class IndexController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewBuilding', Building::class)) {
            return abort(401);
        }
        return view('basic-information-management.buliding-management.index');
    }
}
