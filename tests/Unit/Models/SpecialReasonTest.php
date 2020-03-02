<?php

namespace Test\Unit\Models;

use App\Models\SpecialReason;
use App\Models\SpecialResult;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SpecialReasonTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aSpecialReasonHasManySpecialResult()
    {
        /** @var SpecialReason $specialReason */
        $specialReason = factory(SpecialReason::class)->create();
        $specialResults = factory(SpecialResult::class, 3)->create();
        $specialReason->specialResults()->saveMany($specialResults);

        $this->assertEquals(3, $specialReason->specialResults->count());
    }
}
