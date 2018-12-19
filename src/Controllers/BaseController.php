<?php

namespace AfzalH\UserApi\Controllers;

use AfzalH\UserApi\Requests\StoreUser;
use App\Http\Controllers\Controller;
use App\User;

class BaseController extends Controller
{

    public function createUserFromRequest(StoreUser $request): User
    {
        $user = new User();
        $this->populateFields($request, $user);
        $user->save();
        return $user;
    }

    public function populateFields(StoreUser $request, User $user): void
    {
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->password = bcrypt($request->get('password'));
    }

    public function buildCreationResponse(User $user)
    {
        if ($user->id) {
            return response($user->id, 201);
        } else {
            return response('Error Creating User', 422);
        }
    }
}
