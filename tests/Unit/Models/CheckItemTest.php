<?php

namespace Test\Unit\Models;

use App\Models\Building;
use App\Models\CheckItem;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CheckItemTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aCheckItemBelongsOneBuilding()
    {
        /** @var CheckItem $checkItem */
        $checkItem = factory(CheckItem::class)->create();

        $this->assertTrue($checkItem->building instanceof Building);
    }
}
