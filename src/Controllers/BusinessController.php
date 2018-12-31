<?php

namespace AfzalH\UserApi\Controllers;

use AfzalH\UserApi\Models\Business;
use AfzalH\UserApi\Requests\AddMember;
use App\User;
use Illuminate\Http\Request;
use Auth;

class BusinessController extends BaseController
{
    // route:post businesses
    public function store(Request $request)
    {
        $business = new Business();
        $business->name = $request->get('name');
        $business->owner_id = Auth::id();
        $business->save();

        /** @var User $user */
        $user = Auth::user();
        $user->member_of = $business->id;
        $user->is_owner = true;
        $user->assignRole('admin');
        $user->save();
        return response(['message' => 'done'], 201);
    }

    // route:post add-member
    public function addMember(AddMember $request)
    {
        $member = User::getTheUser($request->user);
        if ($member->isAlreadyAMember()) {
            return response(['message' => 'already a member'], 422);
        }
        $member->makeMember($request->get('business_id'));
        return response(['message' => 'done'], 201);
    }

}
