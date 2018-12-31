<?php

namespace AfzalH\UserApi\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

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
