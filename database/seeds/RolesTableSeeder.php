<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function data() : array
    {
        return [
            ['member', '干事'],
            ['viceLeader', '副部长'],
            ['leader', '部长'],
            ['sysadmin', '系统管理员']
        ];
    }

    public function run(\App\Models\Role $role)
    {
        foreach ($this->data() as $datum) {
            $r = clone $role;
            $r->english_name = $datum[0];
            $r->chinese_name = $datum[1];
            $r->save();
        }
    }
}
