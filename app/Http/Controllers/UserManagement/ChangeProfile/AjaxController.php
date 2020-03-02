<?php

namespace App\Http\Controllers\UserManagement\ChangeProfile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Other\DataTemplate;
use Illuminate\Support\Facades\Hash;

class AjaxController extends Controller
{
    public function changeName()
    {
        /** @var User $user */
        $user = auth()->user();
        $request = request();
        $oldPassword = $user->password;
        $newPassword = $request->input('password');

        $data = app(DataTemplate::class);

        if (Hash::check($newPassword, $oldPassword)) {
            $user->name = $request->input('name');
            $user->save();
            return response()->json($data);
        } else {
            $data->status = 'auth failed';
            return response()->json($data);
        }
    }

    public function changePassword()
    {
        /** @var User $user */
        $user = auth()->user();
        $request = request();

        $newPassword = $request->input('new_password');
        $originPassword = $request->input('origin_password');

        $data = app(DataTemplate::class);
        if (Hash::check($originPassword, $user->password)) {
            $user->password = bcrypt($newPassword);
            $user->save();
            return response()->json($data);
        } else {
            $data->status = 'auth failed';
            return response()->json($data);
        }
    }
}
