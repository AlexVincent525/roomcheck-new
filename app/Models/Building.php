<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Building
 *
 * @property int $id
 * @property string $name
 * @property bool $is_alive
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CheckItem[] $checkItems
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Room[] $rooms
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereIsAlive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Building extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['is_alive' => 'boolean'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function checkItems()
    {
        return $this->hasMany(CheckItem::class);
    }
}
