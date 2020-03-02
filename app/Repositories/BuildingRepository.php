<?php

namespace App\Repositories;

use App\Models\Building;
use Prettus\Repository\Eloquent\BaseRepository;

class BuildingRepository extends BaseRepository
{
    public function model() : string
    {
        return Building::class;
    }
}
