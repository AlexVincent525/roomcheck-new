<?php

namespace App\Http\Controllers\BasicInformationManagement\ItemManagement;

use App\Http\Controllers\Controller;
use App\Models\CheckItem;
use App\Models\User;

class IndexController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewItem', CheckItem::class)) {
            return abort(401);
        }

        return view('basic-information-management.item-management.index');
    }
}
