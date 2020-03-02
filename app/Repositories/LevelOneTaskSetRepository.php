<?php

namespace App\Repositories;

use App\Models\LevelOneTaskSet;
use Prettus\Repository\Eloquent\BaseRepository;

class LevelOneTaskSetRepository extends BaseRepository
{
    public function model() : string
    {
        return LevelOneTaskSet::class;
    }
}
