<?php

namespace AfzalH\UserApi\Controllers;


use AfzalH\UserApi\Requests\StoreUser;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    // route:post users
    public function store(StoreUser $request)
    {
        $user = $this->createUser($request);
        return $this->buildResponse($user);
    }

    /**
     * @param StoreUser $request
     * @return User
     */
    public function createUser(StoreUser $request): User
    {
        $user = new User();
        $this->populateFields($request, $user);
        $user->save();
        return $user;
    }

    /**
     * @param StoreUser $request
     * @param User $user
     */
    public function populateFields(StoreUser $request, User $user): void
    {
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->password = bcrypt($request->get('password'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function buildResponse(User $user)
    {
        if ($user->id) {
            return response($user->id, 201);
        } else {
            return response('Error Creating User', 422);
        }
    }
}
