<?php

namespace App\Http\Controllers\Auth\Login;

use App\Http\Controllers\Controller;
use App\Other\DataTemplate;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function login(
        Request $request,
        DataTemplate $data
    )
    {
        $this->validate($request, [
            'student_id' => 'required',
            'password' => 'required',
        ]);

        $result = auth()->attempt([
            'student_id' => $request->input('student_id'),
            'password' => $request->input('password'),
            'is_alive' => 1
        ]);

        if ($result) {
            $data->username = auth()->user()->name;
            return response()->json($data);
        } else {
            $data->status = 'auth failed';
            return response()->json($data, 401);
        }

    }
}
