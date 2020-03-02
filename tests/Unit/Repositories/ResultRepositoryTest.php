<?php

namespace Test\Unit\Repositories;

use App\Models\Result;
use App\Repositories\ResultRepository;
use Tests\TestCase;

class ResultRepositoryTest extends TestCase
{
    /** @var ResultRepository */
    public $resultRepository;

    public function setUp()
    {
        parent::setUp();
        $this->resultRepository = app(ResultRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(Result::class, $this->resultRepository->model());
    }
}
