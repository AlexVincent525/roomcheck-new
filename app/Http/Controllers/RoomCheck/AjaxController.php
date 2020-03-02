<?php

namespace App\Http\Controllers\RoomCheck;

use App\Http\Controllers\Controller;
use App\Models\CheckTask;
use App\Models\LevelOneTaskSet;
use App\Models\LevelTwoTaskSet;
use App\Models\User;
use App\Other\DataTemplate;
use App\Repositories\LevelOneTaskSetRepository;
use Carbon\Carbon;
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

        /** @var Collection $levelOneTaskSets */
        $levelOneTaskSets = $levelOneTaskSetRepository->orderBy('id', 'desc')->all();
        $request = request();

        $page = $request->input('page');
        $limit = $request->input('limit');
        $total = $levelOneTaskSets->count();

        $levelOneTaskSets = $levelOneTaskSets->forPage($page, $limit);

        $levelOneTemp = new class {
            public $id;
            public $date;
            public $start;
            public $end;
            public $status;
        };

        /** @var DataTemplate $data */
        $data = app(DataTemplate::class);

        $data->total = $total;
        foreach ($levelOneTaskSets as $levelOneTaskSet) {
            /** @var LevelOneTaskSet $levelOneTaskSet */
            $levelOneTaskSetData = clone $levelOneTemp;
            $levelOneTaskSetData->id = $levelOneTaskSet->id;
            $levelOneTaskSetData->date = $levelOneTaskSet->start_time->toDateString();
            $levelOneTaskSetData->start = $levelOneTaskSet->start_time->toDateTimeString();
            $levelOneTaskSetData->end = $levelOneTaskSet->end_time->toDateTimeString();

            if (Carbon::now()->between($levelOneTaskSet->start_time, $levelOneTaskSet->end_time)) {
                $levelOneTaskSetData->status = 'processing';
            } else {
                if (Carbon::now()->gt($levelOneTaskSet->end_time)) {
                    $levelOneTaskSetData->status = 'ended';
                } else {
                    $levelOneTaskSetData->status = 'waiting';
                }
            }
            $data->rows[] = $levelOneTaskSetData;
        }

        return response()->json($data);
    }

    public function levelTwoTaskSetList(LevelOneTaskSetRepository $levelOneTaskSetRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewLevelTwoCheckSet', LevelTwoTaskSet::class)) {
            return abort(401);
        }

        $request = request();
        $levelOneTaskSetId = $request->input('level_one_check_set_id');
        /** @var LevelOneTaskSet $levelOneTaskSet */
        $levelOneTaskSet = $levelOneTaskSetRepository->find($levelOneTaskSetId);
        $levelTwoTaskSets = $levelOneTaskSet->levelTwoTaskSets;
        $total = $levelTwoTaskSets->count();

        $page = $request->input('page');
        $limit = $request->input('limit');
        $levelTwoTaskSets = $levelTwoTaskSets->forPage($page, $limit);

        $levelTwoTaskSetTemp = new class {
            public $id;
            public $room;
            public $building;
            public $assigned;
        };

        /** @var DataTemplate $data */
        $data = app(DataTemplate::class);
        $data->total = $total;

        foreach ($levelTwoTaskSets as $levelTwoTaskSet) {
            /** @var LevelOneTaskSet  $levelTwoTaskSet */
            $levelTwoTaskSetData = clone $levelTwoTaskSetTemp;
            $levelTwoTaskSetData->id = $levelTwoTaskSet->id;
            $levelTwoTaskSetData->room = $levelTwoTaskSet->room->name;
            $levelTwoTaskSetData->building = $levelTwoTaskSet->room->building->name;
            $data->rows[] = $levelTwoTaskSetData;
        }

        return response()->json($data);
    }

    public function checkTaskList(LevelOneTaskSetRepository $levelOneTaskSetRepository)
    {
        $request = request();
        $user = auth()->user();
        $levelOneTaskSetId = $request->input('level_one_check_set_id');
        $page = $request->input('page');
        $limit = $request->input('limit');
        /** @var LevelOneTaskSet $levelOneTaskSet */
        $levelOneTaskSet = $levelOneTaskSetRepository->find($levelOneTaskSetId);
        $checkTasks = $levelOneTaskSet->checkTasks;
        $checkTasks = $checkTasks->filter(function ($item) use ($user) {
            return $item->user_id == $user->id;
        });
        $total = $checkTasks->count();
        $checkTasks = $checkTasks->forPage($page, $limit);

        $checkTaskDataTemplate = new class {
            public $id;
            public $room;
            public $building;
            public $edited;
        };

        $data = app(DataTemplate::class);
        $data->total = $total;

        foreach ($checkTasks as $checkTask) {
            $checkTaskData = clone $checkTaskDataTemplate;
            /** @var CheckTask $checkTask */
            $room = $checkTask->levelTwoTaskSet->room;
            $checkTaskData->id = $checkTask->id;
            $checkTaskData->room = $room->name;
            $checkTaskData->building = $room->building->name;
            if ($checkTask->result == null) {
                $checkTaskData->edited = false;
            } else {
                $checkTaskData->edited = true;
            }
            $data->rows[] = $checkTaskData;
        }

        return response()->json($data);
    }
}
