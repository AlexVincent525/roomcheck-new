<?php

namespace Test\Unit\Models;

use App\Models\CheckTask;
use App\Models\NormalResult;
use App\Models\Result;
use App\Models\SpecialResult;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ResultTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aResultBelongsToOneCheckTask()
    {
        /** @var Result $result */
        $result = factory(Result::class)->create();
        $this->assertTrue($result->checkTask instanceof CheckTask);
    }

    /** @test */
    public function aResultHasManyNormalResult()
    {
        /** @var Result $result */
        $result = factory(Result::class)->create();
        $normalResults = factory(NormalResult::class, 3)->create();
        $result->normalResults()->saveMany($normalResults);
        $this->assertEquals(3, $result->normalResults->count());
    }

    /** @test */
    public function aResultHasOneSpecialResult()
    {
        /** @var Result $result */
        $result = factory(Result::class)->create();
        $specialResult = factory(SpecialResult::class)->create();
        $result->specialResult()->save($specialResult);
        $this->assertTrue($result->specialResult instanceof SpecialResult);
    }
}
