<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NormalResult
 *
 * @property int $id
 * @property int $result_id
 * @property int $check_item_id
 * @property int $score
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NormalResult whereCheckItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NormalResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NormalResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NormalResult whereResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NormalResult whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NormalResult whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\CheckItem $checkItem
 * @property-read \App\Models\Result $result
 */
class NormalResult extends Model
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

    public function checkItem()
    {
        return $this->belongsTo(CheckItem::class);
    }
}
