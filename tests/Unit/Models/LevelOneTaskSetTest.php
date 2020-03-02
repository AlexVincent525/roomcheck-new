<?php

namespace Test\Unit\Models;

use App\Models\LevelOneTaskSet;
use App\Models\LevelTwoTaskSet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LevelOneTaskSetTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aLevelOneSetHasManyLevelTwoTaskSet()
    {
        /** @var LevelOneTaskSet $levelOneTaskSet */
        $levelOneTaskSet = factory(LevelOneTaskSet::class)->create();
        $levelTwoTaskSets = factory(LevelTwoTaskSet::class, 3)->create();
        $levelOneTaskSet->levelTwoTaskSet()->saveMany($levelTwoTaskSets);

        $this->assertEquals(3, $levelOneTaskSet->levelTwoTaskSet->count());
    }
}
