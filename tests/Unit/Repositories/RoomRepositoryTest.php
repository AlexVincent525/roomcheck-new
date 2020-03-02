<?php

namespace Test\Unit\Repositories;

use App\Models\Room;
use App\Repositories\RoomRepository;
use Tests\TestCase;

class RoomRepositoryTest extends TestCase
{
    /** @var RoomRepository */
    public $roomRepository;

    public function setUp()
    {
        parent::setUp();
        $this->roomRepository = app(RoomRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(Room::class, $this->roomRepository->model());
    }
}
