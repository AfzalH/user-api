<?php

namespace AfzalH\UserApi\Controllers;

use AfzalH\UserApi\Models\Business;
use AfzalH\UserApi\Requests\AddMember;
use AfzalH\UserApi\Requests\StoreBusiness;
use App\User;
use Auth;

class BusinessController extends BaseController
{
    // route:post businesses
    public function store(StoreBusiness $request)
    {
        $business = new Business();
        $business->name = $request->get('name');
        $business->owner_id = Auth::id();
        $business->save();
        $this->updateOwner($business);

        return response(['message' => 'done'], 201);
    }

    // route:post add-member
    public function addMember(AddMember $request)
    {
        $user = User::getTheUser($request->user);
        $user->makeMember($request->get('business_id'));
        return response(['message' => 'done'], 201);
    }

    private function updateOwner(Business $business): void
    {
        /** @var User $user */
        $user = Auth::user();
        $user->member_of = $business->id;
        $user->is_owner = true;
        $user->assignRole('admin');
        $user->save();
    }

}
