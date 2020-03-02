<?php

namespace App\Http\Controllers\CheckManagement\DistributeCheck;

use App\Http\Controllers\Controller;
use App\Models\CheckTask;
use App\Models\LevelOneTaskSet;
use App\Models\LevelTwoTaskSet;
use App\Models\User;
use App\Other\DataTemplate;
use App\Repositories\LevelOneTaskSetRepository;
use App\Repositories\LevelTwoTaskSetRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AjaxController extends Controller
{
    public function levelOneCheckTaskSetList(LevelOneTaskSetRepository $levelOneTaskSetRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewLevelOneCheckSet', LevelOneTaskSet::class)) {
            return abort(401);
        }

        /** @var Collection $levelOneTaskSets */
        $levelOneTaskSets = $levelOneTaskSetRepository->all();
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
            public $users;
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
            if ($levelTwoTaskSet->checkTasks->count() == 0) {
                $levelTwoTaskSetData->assigned = false;
                $levelTwoTaskSetData->users = [];
            } else {
                $levelTwoTaskSetData->assigned = true;
                foreach ($levelTwoTaskSet->checkTasks as $checkTask) {
                    /** @var CheckTask $checkTask */
                    $levelTwoTaskSetData->users[] = $checkTask->user->name;
                }
            }
            $data->rows[] = $levelTwoTaskSetData;
        }

        return response()->json($data);
    }

    public function userList(UserRepository $userRepository)
    {

        $request = request();
        $keyWord = $request->input('search');
        if ($keyWord == null) {
            /** @var Collection $users */
            $users = $userRepository->findByField('is_alive', '1');
        } else {
            /** @var Collection $users */
            $users = $userRepository->findWhere([['is_alive', '=', '1'], ['name', 'LIKE', '%' . $keyWord . '%']]);
        }
        $total = $users->count();

        $levelTwoCheckTaskSetId = $request->input('level_two_check_set_id');
        $levelOneCheckTaskSetId = $request->input('level_one_check_set_id');
        $page = $request->input('page');
        $limit = $request->input('limit');

        $users = $users->forPage($page, $limit);

        $userDataTemp = new class {
            public $id;
            public $name;
            public $student_id;
            public $assigned_to_this;
            public $assigned_room_name;
        };

        /** @var DataTemplate $data */
        $data = app(DataTemplate::class);
        $data->total = $total;
        $data->rows = [];

        foreach ($users as $user) {
            /** @var User $user */
            $userData = clone $userDataTemp;
            $userData->id = $user->id;
            $userData->name = $user->name;
            $userData->student_id = $user->student_id;
            foreach ($user->checkTasks as $checkTask) {
                if ($checkTask->levelTwoTaskSet->id == $levelTwoCheckTaskSetId) {
                    $userData->assigned_to_this = true;
                } else {
                    $userData->assigned_to_this = false;
                }
                if ($checkTask->levelTwoTaskSet->levelOneTaskSet->id == $levelOneCheckTaskSetId) {
                    $userData->assigned_room_name[] = $checkTask->levelTwoTaskSet->room->name .
                        '#' . $checkTask->levelTwoTaskSet->room->building->name;
                } else {
                    $userData->assigned_room_name = [];
                }
            }
            $data->rows[] = $userData;
        }

        return response()->json($data);

    }

    public function createCheckTask(
        LevelTwoTaskSetRepository $levelTwoTaskSetRepository,
        UserRepository $userRepository,
        CheckTask $checkTask
    )
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('addCheckTask', CheckTask::class)) {
            return abort(401);
        }

        $request = request();
        $levelTwoCheckTaskSetId = $request->input('level_two_check_set_id');
        /** @var LevelTwoTaskSet $levelTwoCheckTaskSet */
        $levelTwoCheckTaskSet = $levelTwoTaskSetRepository->find($levelTwoCheckTaskSetId);

        $userIds = $request->input('users');

        //如果已经有检查任务存在，就是修改，如果没有就是创建
        if ($levelTwoCheckTaskSet->checkTasks->count() != 0) {
            //修改直接删除原来的任务
            foreach ($levelTwoCheckTaskSet->checkTasks as $oldCheckTask) {
                $oldCheckTask->delete();
            }
        }
        foreach ($userIds as $userId) {
            $user = $userRepository->find($userId);
            $newCheckTask = clone $checkTask;
            $newCheckTask->levelTwoTaskSet()->associate($levelTwoCheckTaskSet);
            $newCheckTask->user()->associate($user);
            $newCheckTask->save();
        }

        $data = app(DataTemplate::class);

        return response()->json($data);
    }
}