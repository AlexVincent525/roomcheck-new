<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TaskSet
 *
 * @property int $id
 * @property \Carbon\Carbon|null $start_time
 * @property \Carbon\Carbon|null $end_time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LevelOneTaskSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LevelOneTaskSet whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LevelOneTaskSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LevelOneTaskSet whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LevelOneTaskSet whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LevelTwoTaskSet[] $levelTwoTaskSets
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CheckTask[] $checkTasks
 */
class LevelOneTaskSet extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'level_one_task_set';

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
        'start_time',
        'end_time'
    ];

    public function levelTwoTaskSets()
    {
        return $this->hasMany(LevelTwoTaskSet::class);
    }

    public function checkTasks()
    {
        return $this->hasManyThrough(CheckTask::class, LevelTwoTaskSet::class);
    }
}
