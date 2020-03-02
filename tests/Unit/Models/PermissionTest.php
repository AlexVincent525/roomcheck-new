<?php

namespace Test\Unit\Models;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aPermissionBelongsToManyRoles()
    {
        /** @var Permission $permission */
        $permission = factory(Permission::class)->create();
        $roles = factory(Role::class, 3)->create();
        $permission->roles()->saveMany($roles);
        $this->assertEquals(3, $permission->roles->count());
    }
}