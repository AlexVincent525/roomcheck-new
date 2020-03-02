<?php

namespace App\Repositories;

use App\Models\Result;
use Prettus\Repository\Eloquent\BaseRepository;

class ResultRepository extends BaseRepository
{
    public function model() : string
    {
        return Result::class;
    }
}
