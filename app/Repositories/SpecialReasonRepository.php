<?php

namespace App\Repositories;

use App\Models\SpecialReason;
use Prettus\Repository\Eloquent\BaseRepository;

class SpecialReasonRepository extends BaseRepository
{
    public function model() : string
    {
        return SpecialReason::class;
    }
}
