<?php

namespace Test\Unit\Repositories;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Tests\TestCase;

class RoleRepositoryTest extends TestCase
{
    /** @var RoleRepository */
    public $roleRepository;

    public function setUp()
    {
        parent::setUp();
        $this->roleRepository = app(RoleRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(Role::class, $this->roleRepository->model());
    }
}
