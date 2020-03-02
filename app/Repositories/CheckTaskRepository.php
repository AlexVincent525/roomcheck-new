<?php

namespace App\Repositories;

use App\Models\CheckTask;
use Prettus\Repository\Eloquent\BaseRepository;

class CheckTaskRepository extends BaseRepository
{
    public function model() : string
    {
        return CheckTask::class;
    }
}
