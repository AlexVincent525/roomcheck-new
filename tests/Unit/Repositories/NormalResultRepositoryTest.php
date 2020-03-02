<?php

namespace Test\Unit\Repositories;

use App\Models\NormalResult;
use App\Repositories\NormalResultRepository;
use Tests\TestCase;

class NormalResultRepositoryTest extends TestCase
{
    /** @var NormalResultRepository */
    public $normalResultRepository;

    public function setUp()
    {
        parent::setUp();
        $this->normalResultRepository = app(NormalResultRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(NormalResult::class, $this->normalResultRepository->model());
    }
}
