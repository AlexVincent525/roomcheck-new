<?php

namespace Test\Unit\Models;

use App\Models\Result;
use App\Models\SpecialReason;
use App\Models\SpecialResult;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SpecialResultTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aSpecialResultBelongsToOneResult()
    {
        /** @var SpecialResult $specialResult */
        $specialResult = factory(SpecialResult::class)->create();
        $this->assertTrue($specialResult->result instanceof Result);
    }

    /** @test */
    public function aSpecialResultBelongsToOneReason()
    {
        /** @var SpecialResult $specialResult */
        $specialResult = factory(SpecialResult::class)->create();
        $this->assertTrue($specialResult->specialReason instanceof SpecialReason);
    }
}
