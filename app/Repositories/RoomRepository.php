<?php

namespace App\Repositories;

use App\Models\Room;
use Prettus\Repository\Eloquent\BaseRepository;

class RoomRepository extends BaseRepository
{
    public function model() : string
    {
        return Room::class;
    }
}
