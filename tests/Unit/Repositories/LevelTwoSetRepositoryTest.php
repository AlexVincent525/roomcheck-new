<?php

namespace Test\Unit\Repositories;

use App\Models\LevelTwoTaskSet;
use App\Repositories\LevelTwoTaskSetRepository;
use Tests\TestCase;

class LevelTwoSetRepositoryTest extends TestCase
{
    /** @var LevelTwoTaskSetRepository */
    public $levelTwoTaskSetRepository;

    public function setUp()
    {
        parent::setUp();
        $this->levelTwoTaskSetRepository = app(LevelTwoTaskSetRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(LevelTwoTaskSet::class, $this->levelTwoTaskSetRepository->model());
    }
}
