<?php

namespace Test\Unit\Repositories;

use App\Models\LevelOneTaskSet;
use App\Repositories\LevelOneTaskSetRepository;
use Tests\TestCase;

class LevelOneTaskSetRepositoryTest extends TestCase
{
    /** @var LevelOneTaskSetRepository */
    public $levelOneTaskSetRepository;

    public function setUp()
    {
        parent::setUp();
        $this->levelOneTaskSetRepository = app(LevelOneTaskSetRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(LevelOneTaskSet::class, $this->levelOneTaskSetRepository->model());
    }
}
