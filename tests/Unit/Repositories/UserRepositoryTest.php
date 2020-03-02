<?php

namespace Test\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    /** @var UserRepository */
    public $userRepository;

    public function setUp()
    {
        parent::setUp();
        $this->userRepository = app(UserRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(User::class, $this->userRepository->model());
    }
}
