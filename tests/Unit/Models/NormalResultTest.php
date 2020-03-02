<?php

namespace Test\Unit\Models;

use App\Models\CheckItem;
use App\Models\NormalResult;
use App\Models\Result;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NormalResultTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aNormalResultBelongsToOneResult()
    {
        /** @var NormalResult $normalResult */
        $normalResult = factory(NormalResult::class)->create();
        $this->assertTrue($normalResult->result instanceof Result);
    }

    /** @test */
    public function aNormalResultBelongsToOneCheckItem()
    {
        /** @var NormalResult $normalResult */
        $normalResult = factory(NormalResult::class)->create();
        $this->assertTrue($normalResult->checkItem instanceof CheckItem);
    }
}