<?php

namespace AfzalH\UserApi\Controllers;

use AfzalH\UserApi\Models\Business;
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
        return response(['message' => 'done'], 201);
    }

}
