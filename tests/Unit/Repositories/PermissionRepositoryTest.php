<?php

namespace Test\Unit\Repositories;

use App\Models\Permission;
use App\Repositories\PermissionRepository;
use Tests\TestCase;

class PermissionRepositoryTest extends TestCase
{
    /** @var PermissionRepository */
    public $permissionRepository;

    public function setUp()
    {
        parent::setUp();
        $this->permissionRepository = app(PermissionRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(Permission::class, $this->permissionRepository->model());
    }
}
