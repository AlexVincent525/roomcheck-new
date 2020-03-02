<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SpecialReason
 *
 * @property int $id
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialReason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialReason whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialReason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialReason whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SpecialResult[] $specialResults
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialReason whereName($value)
 */
class SpecialReason extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function specialResults()
    {
        return $this->hasMany(SpecialResult::class);
    }
}
