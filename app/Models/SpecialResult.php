<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SpecialResult
 *
 * @property int $id
 * @property int $result_id
 * @property int $special_reason_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialResult whereResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialResult whereSpecialReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialResult whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Result $result
 * @property-read \App\Models\SpecialReason $specialReason
 */
class SpecialResult extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public function specialReason()
    {
        return $this->belongsTo(SpecialReason::class);
    }
}
