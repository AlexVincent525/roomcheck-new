<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function loginTestUser()
    {
        \Auth::loginUsingId(1);
        return redirect(route('home-page'));
    }
}
