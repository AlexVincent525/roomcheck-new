<?php

namespace Test\Unit\Repositories;

use App\Models\SpecialReason;
use App\Repositories\SpecialReasonRepository;
use Tests\TestCase;

class SpecialReasonRepositoryTest extends TestCase
{
    /** @var SpecialReasonRepository */
    public $specialReasonRepository;

    public function setUp()
    {
        parent::setUp();
        $this->specialReasonRepository = app(SpecialReasonRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(SpecialReason::class, $this->specialReasonRepository->model());
    }
}
