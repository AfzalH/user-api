<?php

namespace AfzalH\UserApi\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * AfzalH\UserApi\Models\Business
 *
 * @property int $id
 * @property string $name
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $members
 * @property-read \App\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|\AfzalH\UserApi\Models\Business newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\AfzalH\UserApi\Models\Business newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\AfzalH\UserApi\Models\Business query()
 * @method static \Illuminate\Database\Eloquent\Builder|\AfzalH\UserApi\Models\Business whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AfzalH\UserApi\Models\Business whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AfzalH\UserApi\Models\Business whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AfzalH\UserApi\Models\Business whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\AfzalH\UserApi\Models\Business whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Business extends Model
{
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->hasMany(User::class, 'member_of');
    }
}
