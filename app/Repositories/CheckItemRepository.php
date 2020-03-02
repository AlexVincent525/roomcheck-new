<?php

namespace App\Repositories;

use App\Models\CheckItem;
use Prettus\Repository\Eloquent\BaseRepository;

class CheckItemRepository extends BaseRepository
{
    public function model() : string
    {
        return CheckItem::class;
    }
}
