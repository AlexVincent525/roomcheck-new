<?php

namespace App\Providers;

use App\Models\Building;
use App\Models\CheckItem;
use App\Models\CheckTask;
use App\Models\LevelOneTaskSet;
use App\Models\LevelTwoTaskSet;
use App\Models\Room;
use App\Models\User;
use App\Policies\BuildingPolicy;
use App\Policies\CheckItemPolicy;
use App\Policies\CheckTaskPolicy;
use App\Policies\LevelOneCheckTaskSetPolicy;
use App\Policies\LevelTwoCheckTaskSetPolicy;
use App\Policies\RoomPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Room::class => RoomPolicy::class,
        Building::class => BuildingPolicy::class,
        CheckItem::class => CheckItemPolicy::class,
        LevelOneTaskSet::class => LevelOneCheckTaskSetPolicy::class,
        LevelTwoTaskSet::class => LevelTwoCheckTaskSetPolicy::class,
        CheckTask::class => CheckTaskPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
