<?php

namespace App\Repositories;

use App\Models\NormalResult;
use Prettus\Repository\Eloquent\BaseRepository;

class NormalResultRepository extends BaseRepository
{
    public function model() : string
    {
        return NormalResult::class;
    }
}
