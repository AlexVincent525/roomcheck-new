<?php

namespace Test\Unit\Models;

use App\Models\Building;
use App\Models\CheckItem;
use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BuildingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aBuildingHasManyRooms()
    {
        /** @var Building $building */
        $building = factory(Building::class)->create();
        $rooms = factory(Room::class, 3)->create();
        $building->rooms()->saveMany($rooms);

        $this->assertEquals(3, $building->rooms->count());
    }

    /** @test */
    public function aBuildingHasManyCheckItems()
    {
        /** @var Building $building */
        $building = factory(Building::class)->create();
        $checkItems = factory(CheckItem::class, 3)->create();
        $building->checkItems()->saveMany($checkItems);

        $this->assertEquals(3, $building->checkItems->count());
    }
}
