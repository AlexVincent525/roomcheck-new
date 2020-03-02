<?php

namespace Test\Unit\Repositories;

use App\Models\SpecialResult;
use App\Repositories\SpecialResultRepository;
use Tests\TestCase;

class SpecialResultRepositoryTest extends TestCase
{
    /** @var SpecialResultRepository */
    public $specialResultRepository;

    public function setUp()
    {
        parent::setUp();
        $this->specialResultRepository = app(SpecialResultRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(SpecialResult::class, $this->specialResultRepository->model());
    }
}
