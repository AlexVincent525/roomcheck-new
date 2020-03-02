<?php

namespace App\Repositories;

use App\Models\SpecialResult;
use Prettus\Repository\Eloquent\BaseRepository;

class SpecialResultRepository extends BaseRepository
{
    public function model() : string
    {
        return SpecialResult::class;
    }
}
