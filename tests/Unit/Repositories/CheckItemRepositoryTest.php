<?php

namespace Test\Unit\Repositories;

use App\Models\CheckItem;
use App\Repositories\CheckItemRepository;
use Tests\TestCase;

class CheckItemRepositoryTest extends TestCase
{
    /** @var CheckItemRepository */
    public $checkItemRepository;

    public function setUp()
    {
        parent::setUp();
        $this->checkItemRepository = app(CheckItemRepository::class);
    }

    public function testModel()
    {
        $this->assertEquals(CheckItem::class, $this->checkItemRepository->model());
    }
}
