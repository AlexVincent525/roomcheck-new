<?php

namespace App\Repositories;

use App\Models\LevelTwoTaskSet;
use Prettus\Repository\Eloquent\BaseRepository;

class LevelTwoTaskSetRepository extends BaseRepository
{
    public function model() : string
    {
        return LevelTwoTaskSet::class;
    }
}
