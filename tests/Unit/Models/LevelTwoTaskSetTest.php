<?php

namespace Test\Unit\Models;

use App\Models\CheckTask;
use App\Models\LevelTwoTaskSet;
use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LevelTwoTaskSetTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aLevelTwoSetBelongsOneRoom()
    {
        /** @var LevelTwoTaskSet $levelTwoTaskSet */
        $levelTwoTaskSet = factory(LevelTwoTaskSet::class)->create();

        $this->assertTrue($levelTwoTaskSet->room instanceof Room);
    }

    /** @test */
    public function aLevelTwoSetHasManyCheckTasks()
    {
        /** @var LevelTwoTaskSet $levelTwoTaskSet */
        $levelTwoTaskSet = factory(LevelTwoTaskSet::class)->create();

        $checkTasks = factory(CheckTask::class, 2)->create();
        $levelTwoTaskSet->checkTasks()->saveMany($checkTasks);

        $this->assertEquals(2, $levelTwoTaskSet->checkTasks->count());
    }
}
