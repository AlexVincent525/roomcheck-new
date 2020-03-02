<?php

namespace Test\Unit\Repositories;

use App\Models\Building;
use App\Repositories\BuildingRepository;
use Tests\TestCase;

class BuildingRepositoryTest extends TestCase
{
    /** @var BuildingRepository */
    public $buildingRepository;

    public function setUp()
    {
        parent::setUp();
        $this->buildingRepository = app(BuildingRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(Building::class, $this->buildingRepository->model());
    }
}
