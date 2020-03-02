<?php

namespace App\Http\Controllers\BasicInformationManagement\BuildingManagement;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\CheckItem;
use App\Models\User;
use App\Other\DataTemplate;
use App\Repositories\BuildingRepository;
use Illuminate\Support\Collection;

class AjaxController extends Controller
{
    public function buildingList(BuildingRepository $buildingRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewBuilding', Building::class)) {
            return abort(401);
        }
        $request = request();
        /** @var Collection $buildings */
        $buildings = $buildingRepository->all();
        $buildingCount = $buildings->count();
        $page = $request->input('page');
        $limit = $request->input('limit');

        $buildings = $buildings->forPage($page, $limit);

        $buildingTemp = new class
        {
            public $id;
            public $name;
            public $rooms;
            public $active;
            public $items = [];
        };

        /** @var DataTemplate $data */
        $data = app(DataTemplate::class);
        $data->total = $buildingCount;
        foreach ($buildings as $building) {
            /** @var Building $building */
            $buildingData = clone $buildingTemp;
            $buildingData->id = $building->id;
            $buildingData->name = $building->name;
            $buildingData->rooms = $building->rooms->count();
            $buildingData->active = $building->is_alive;
            foreach ($building->checkItems as $checkItem) {
                $buildingData->items[] = $checkItem->name;
            }
            $data->rows[] = $buildingData;
        }

        return response()->json($data);
    }

    public function changeBuildingStatus(BuildingRepository $buildingRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('activeBuilding', Building::class)) {
            return abort(401);
        }

        $buildingIds = request()->input('buildings');
        foreach ($buildingIds as $buildingId) {
            /** @var Building $building */
            $building = $buildingRepository->find($buildingId);
            if ($building->is_alive) {
                $building->is_alive = false;
            } else {
                $building->is_alive = true;
            }
            $building->save();
        }
        $data = app(DataTemplate::class);
        return response()->json($data);
    }

    public function addBuilding(
        Building $building,
        BuildingRepository $buildingRepository,
        CheckItem $checkItem
    )
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('addBuilding', Building::class)) {
            return abort(401);
        }
        $request = request();

        $buildingName = $request->input('building_name');
        $buildingItems = $request->input('building_items');
        /** @var Collection $existBuilding */
        $existBuilding = $buildingRepository->findByField('name', $buildingName);
        if ($existBuilding->count() != 0) {
            return abort(409, 'Building name ALREADY EXISTS!');
        }

        $building->name = $buildingName;
        $buildingCheckItems = [];
        foreach ($buildingItems as $buildingItem) {
            $buildingCheckItem = clone $checkItem;
            $buildingCheckItem->name = $buildingItem['itemName'];
            $buildingCheckItem->full_score = $buildingItem['fullScore'];
            $buildingCheckItems[] = $buildingCheckItem;
        }

        $building->save();
        $building->checkItems()->saveMany($buildingCheckItems);
        $data = app(DataTemplate::class);
        return response()->json($data);
    }
}
