<?php

namespace App\Http\Controllers\BasicInformationManagement\RoomManagement;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use App\Models\User;
use App\Other\DataTemplate;
use App\Repositories\BuildingRepository;
use App\Repositories\RoomRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class AjaxController extends Controller
{
    public function roomList(RoomRepository $roomRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('viewDormitory', Room::class)) {
            return abort(401);
        }
        $request = request();
        /** @var Collection $rooms */
        $rooms = $roomRepository->all();

        /** @var DataTemplate $data */
        $data = app(DataTemplate::class);
        $data->total = $rooms->count();

        $data->rows = [];

        $roomTemplate = new Class {
            public $id;
            public $number;
            public $building;
            public $active;
        };

        $limit = $request->input('limit');
        $page = $request->input('page');

        $rooms = $rooms->forPage($page, $limit);
        foreach ($rooms as $room) {
            /** @var Room $room */
            $roomData = clone $roomTemplate;
            $roomData->id = $room->id;
            $roomData->number = $room->name;
            $roomData->building = $room->building->name;
            $roomData->active = $room->is_alive;
            $data->rows[] = $roomData;
        }

        return response()->json($data);
    }

    public function addRoom(BuildingRepository $buildingRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('addDormitory', Room::class)) {
            return abort(401);
        }

        $request = request();
        $roomName = $request->input('room_name');
        $buildingId = $request->input('building_id');
        /** @var Building $building */
        $building = $buildingRepository->find($buildingId);
        /** @var Room $room */
        $room = app(Room::class);
        $room->name = $roomName;
        $room->building()->associate($building);
        $room->save();
        $data = app(DataTemplate::class);
        return response()->json($data);
    }

    public function buildingList(BuildingRepository $buildingRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('addDormitory', Room::class)) {
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

    public function changeRoomStatus(RoomRepository $roomRepository)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('activeDormitory', Room::class)) {
            return abort(401);
        }

        $roomIds = request()->input('rooms');
        foreach ($roomIds as $roomId) {
            /** @var Room $room */
            $room = $roomRepository->find($roomId);
            if ($room->is_alive) {
                $room->is_alive = false;
            } else {
                $room->is_alive = true;
            }
            $room->save();
        }
        $data = app(DataTemplate::class);
        return response()->json($data);
    }

    public function importRoom(
        BuildingRepository $buildingRepository
    )
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->cannot('addDormitory', Room::class)) {
            return abort(401);
        }

        $request = request();
        $file = $request->file('file_data');
        $reader = Excel::load($file);
        try {
            $reader->each(function ($row) use ($buildingRepository) {
                \DB::transaction(function () use ($buildingRepository, $row) {
                    if ($row->room_name != null) {
                        /** @var Room $newRoom */
                        $buildingName = $row->building_name;
                        $roomName = $row->room_name;
                        /** @var Building $building */
                        $building = $buildingRepository->findByField('name', $buildingName)->first();
                        if ($building == null) {
                            throw new \Exception('building ' . $buildingName . ' does not exist');
                        }
                        $rooms = $building->rooms;
                        $rooms->search(function ($room) use ($roomName) {
                            return $room->name == $roomName;
                        });
                        $newRoom = app(Room::class);
                        $newRoom->name = $row->room_name;
                        $newRoom->building()->associate($building);
                        $newRoom->save();
                    }
                });
            });
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return response()->json($msg, 500);
        }

        $data = app(DataTemplate::class);

        return response()->json($data);
    }
}
