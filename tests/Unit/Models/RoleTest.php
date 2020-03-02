<?php

namespace Test\Unit\Models;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aRoleHasManyPermissions()
    {
        /** @var Role $role */
        $role = factory(Role::class)->create();
        $permissions = factory(Permission::class, 3)->create();
        $role->permissions()->saveMany($permissions);
        $this->assertEquals(3, $role->permissions->count());
    }
}