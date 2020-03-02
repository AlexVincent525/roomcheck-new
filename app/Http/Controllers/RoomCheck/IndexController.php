<?php

namespace App\Http\Controllers\RoomCheck;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('room-check.index');
    }
}
