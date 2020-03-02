<?php

namespace Test\Unit\Models;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function aRoomBelongsToOneBuilding()
    {
        /** @var Room $room */
        $room = factory(Room::class)->create();
        $this->assertTrue($room->building instanceof Building);
    }
}
