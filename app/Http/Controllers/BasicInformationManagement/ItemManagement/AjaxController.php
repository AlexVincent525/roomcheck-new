<?php

namespace App\Http\Controllers\BasicInformationManagement\ItemManagement;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\CheckItem;
use App\Models\User;
use App\Other\DataTemplate;
use App\Repositories\BuildingRepository;
use App\Repositories\CheckItemRepository;
use Illuminate\Support\Collection;

class AjaxController extends Controller
{
    public function buildingList(BuildingRepository $buildingRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewItem', CheckItem::class)) {
            return abort(401);
        }

        $buildingDataTemp = new class {
            public $id;
            public $name;
        };

        /** @var Collection $buildings */
        $buildings = $buildingRepository->all();
        $buildings = $buildings->filter(function ($building) {
            return $building->is_alive;
        });
        $data = app(DataTemplate::class);

        $data->total = $buildings->count();
        $data->rows = [];
        foreach ($buildings as $building) {
            /** @var Building $building */
            $buildingData = clone $buildingDataTemp;
            $buildingData->name = $building->name;
            $buildingData->id = $building->id;
            $data->rows[] = $buildingData;
        }

        return response()->json($data);
    }

    public function itemList(BuildingRepository $buildingRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewItem', CheckItem::class)) {
            return abort(401);
        }

        $checkItemDataTemp = new class {
            public $id;
            public $name;
            public $point;
        };
        /** @var Building $building */
        $building = $buildingRepository->find(request()->input('building_id'));
        $checkItems = $building->checkItems;

        /** @var DataTemplate $data */
        $data = app(DataTemplate::class);
        $data->total = $checkItems->count();
        $data->rows = [];

        foreach ($checkItems as $checkItem) {
            $checkItemData = clone $checkItemDataTemp;
            /** @var CheckItem $checkItem */
            $checkItemData->id = $checkItem->id;
            $checkItemData->name = $checkItem->name;
            $checkItemData->point = $checkItem->full_score;
            $data->rows[] = $checkItemData;
        }

        return response()->json($data);
    }

    public function editItem(CheckItemRepository $checkItemRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('editItem', CheckItem::class)) {
            return abort(401);
        }
        $request = request();
        $checkItemId = $request->input('id');
        /** @var CheckItem $checkItem */
        $checkItem = $checkItemRepository->find($checkItemId);
        $checkItem->name = $request->input('name');
        $checkItem->full_score = $request->input('point');
        $checkItem->save();

        $data = app(DataTemplate::class);
        return response()->json($data);
    }
}
