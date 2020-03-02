<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CheckItem
 *
 * @property int $id
 * @property string $name
 * @property int $full_score
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckItem whereFullScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Building $building
 * @property int|null $building_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CheckItem whereBuildingId($value)
 */
class CheckItem extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
