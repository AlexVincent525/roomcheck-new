<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Result
 *
 * @property int $id
 * @property int $check_task_id
 * @property int $result_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereCheckTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereResultType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\CheckTask $checkTask
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\NormalResult[] $normalResults
 * @property-read \App\Models\SpecialResult $specialResult
 */
class Result extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function checkTask()
    {
        return $this->belongsTo(CheckTask::class);
    }

    public function normalResults()
    {
        return $this->hasMany(NormalResult::class);
    }

    public function specialResult()
    {
        return $this->hasOne(SpecialResult::class);
    }
}
