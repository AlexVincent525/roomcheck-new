<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TaskSet
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LevelOneTaskSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LevelOneTaskSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LevelOneTaskSet whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CheckTask[] $checkTasks
 * @property-read \App\Models\Room $room
 * @property int $level_one_task_set_id
 * @property int $room_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LevelTwoTaskSet whereLevelOneTaskSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LevelTwoTaskSet whereRoomId($value)
 * @property-read \App\Models\LevelOneTaskSet $levelOneTaskSet
 */
class LevelTwoTaskSet extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'level_two_task_set';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_time', 'end_time'
    ];

    public function checkTasks()
    {
        return $this->hasMany(CheckTask::class);
    }

    public function levelOneTaskSet()
    {
        return $this->belongsTo(LevelOneTaskSet::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
