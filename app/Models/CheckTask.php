<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CheckTask
 *
 * @property int $id
 * @property int $user_id
 * @property int $level_two_task_set_id
 * @property boolean $is_complete
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckTask whereIsComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckTask whereLevelTwoTaskSet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckTask whereTaskSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckTask whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckTask whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\LevelTwoTaskSet $levelTwoTaskSet
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Result $result
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckTask whereLevelTwoTaskSetId($value)
 */
class CheckTask extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['is_complete' => 'boolean'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function levelTwoTaskSet()
    {
        return $this->belongsTo(LevelTwoTaskSet::class);
    }

    public function result()
    {
        return $this->hasOne(Result::class);
    }
}
