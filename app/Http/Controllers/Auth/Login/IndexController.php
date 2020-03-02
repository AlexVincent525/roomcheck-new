<?php

namespace App\Http\Controllers\Auth\Login;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
}
