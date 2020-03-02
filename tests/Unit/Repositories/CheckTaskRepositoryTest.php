<?php

namespace Test\Unit\Repositories;

use App\Models\CheckTask;
use App\Repositories\CheckTaskRepository;
use Tests\TestCase;

class CheckTaskRepositoryTest extends TestCase
{
    /** @var CheckTaskRepository */
    public $checkTaskRepository;

    public function setUp()
    {
        parent::setUp();
        $this->checkTaskRepository = app(CheckTaskRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(CheckTask::class, $this->checkTaskRepository->model());
    }
}
