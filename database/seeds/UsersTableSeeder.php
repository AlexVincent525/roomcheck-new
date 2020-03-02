<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(\App\Models\User $user)
    {
        $user->name = '初始用户';
        $user->student_id = 00000000;
        $user->password = bcrypt('123456789');
        /** @var \App\Repositories\RoleRepository $roleRepository */
        $roleRepository = app(\App\Repositories\RoleRepository::class);
        $role = $roleRepository->findWhere(['english_name' => 'sysadmin'])->first();
        $user->role()->associate($role);
        $user->save();
    }
}
