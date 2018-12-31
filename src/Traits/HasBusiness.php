<?php

namespace AfzalH\UserApi\Traits;

use AfzalH\UserApi\Models\Business;
use App\User;

trait HasBusiness
{
    public function business()
    {
        return $this->hasOne(Business::class, 'owner_id');
    }

    public function isMemberOf($business_id)
    {
        if (empty($business_id)) return false;
        return $this->member_of == $business_id;
    }

    public static function getTheUser($user_id_or_email)
    {
        if (is_numeric($user_id_or_email)) {
            return User::findOrFail($user_id_or_email);
        } else {
            return User::whereEmail($user_id_or_email)->firstOrFail();
        }
    }

    public function makeMember($business_id)
    {
        $this->member_of = $business_id;
        $this->save();
    }

    public function isAlreadyAMember()
    {
        return ($this->member_of != null);
    }
}
