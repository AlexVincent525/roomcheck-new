<?php

namespace Test\Unit\Models;

use App\Models\CheckTask;
use App\Models\LevelTwoTaskSet;
use App\Models\Result;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CheckTaskTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aTaskBelongsToAnUser()
    {
        /** @var CheckTask $checkTask */
        $checkTask = factory(CheckTask::class)->create();

        $this->assertTrue($checkTask->user instanceof User);
    }

    /** @test */
    public function aTaskBelongsToOneSet()
    {
        /** @var CheckTask $checkTask */
        $checkTask = factory(CheckTask::class)->create();
        $this->assertTrue($checkTask->levelTwoTaskSet instanceof LevelTwoTaskSet);
    }

    /** @test */
    public function aTaskHasOneResult()
    {
        /** @var CheckTask $checkTask */
        $checkTask = factory(CheckTask::class)->create();
        $result = factory(Result::class)->create();
        $checkTask->result()->save($result);
        $this->assertTrue($checkTask->result instanceof Result);
    }
}
