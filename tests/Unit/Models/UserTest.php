<?php

namespace Test\Unit\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function anUserBelongsOneRoles()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $this->assertTrue($user->role instanceof Role);
    }
}