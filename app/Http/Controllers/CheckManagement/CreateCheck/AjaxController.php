<?php

namespace App\Http\Controllers\CheckManagement\CreateCheck;

use App\Http\Controllers\Controller;
use App\Models\LevelOneTaskSet;
use App\Models\LevelTwoTaskSet;
use App\Models\Room;
use App\Models\User;
use App\Other\DataTemplate;
use App\Repositories\LevelOneTaskSetRepository;
use App\Repositories\RoomRepository;
use Illuminate\Support\Collection;

class AjaxController extends Controller
{
    public function levelOneCheckList(LevelOneTaskSetRepository $levelOneTaskSetRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewLevelOneCheckSet', LevelOneTaskSet::class)) {
            return abort(401);
        }

        $request = request();
        /** @var Collection $levelOneTaskSets */
        $levelOneTaskSets = $levelOneTaskSetRepository->all();
        $total = $levelOneTaskSets->count();
        $page = $request->input('page');
        $limit = $request->input('limit');

        $levelOneTaskSets = $levelOneTaskSets->forPage($page, $limit);
        $levelOneTemp = new class {
            public $id;
            public $date;
            public $start;
            public $end;
        };

        /** @var DataTemplate $data */
        $data = app(DataTemplate::class);
        $data->total = $total;

        foreach ($levelOneTaskSets as $levelOneTaskSet) {
            /** @var LevelOneTaskSet $levelOneTaskSet */
            $levelOneSetData = clone $levelOneTemp;
            $levelOneSetData->id = $levelOneTaskSet->id;
            $levelOneSetData->date = $levelOneTaskSet->start_time->toDateString();
            $levelOneSetData->start = $levelOneTaskSet->start_time->toDateTimeString();
            $levelOneSetData->end = $levelOneTaskSet->end_time->toDateTimeString();
            $data->rows[] = $levelOneSetData;
        }

        return response()->json($data);
    }

    public function createLevelOneTaskSet(
        LevelOneTaskSet $levelOneTaskSet,
        RoomRepository $roomRepository,
        LevelTwoTaskSet $levelTwoTaskSet
    )
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('addLevelOneCheckSet', LevelOneTaskSet::class)) {
            return abort(401);
        }

        $request = request();
        $startTime = $request->input('start');
        $endTime = $request->input('end');
        $levelOneTaskSet->start_time = $startTime;
        $levelOneTaskSet->end_time = $endTime;
        $levelOneTaskSet->save();

        //创建了一级检查夹后为每个活跃的宿舍创建二级检查夹
        $rooms = $roomRepository->all();
        foreach ($rooms as $room) {
            /** @var Room $room */
            if ($room->is_alive) {
                $newLevelTwoTaskSet = clone $levelTwoTaskSet;
                $newLevelTwoTaskSet->room()->associate($room);
                $newLevelTwoTaskSet->levelOneTaskSet()->associate($levelOneTaskSet);
                $newLevelTwoTaskSet->save();
            }
        }

        $data = app(DataTemplate::class);
        return response()->json($data);
    }
}
