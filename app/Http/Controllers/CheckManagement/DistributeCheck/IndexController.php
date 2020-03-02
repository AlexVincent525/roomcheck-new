<?php

namespace App\Http\Controllers\CheckManagement\DistributeCheck;

use App\Http\Controllers\Controller;
use App\Models\LevelOneTaskSet;
use App\Models\User;

class IndexController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewLevelOneCheckSet', LevelOneTaskSet::class)) {
            return abort(401);
        }

        return view('check-management.check-distribute.index');
    }
}
