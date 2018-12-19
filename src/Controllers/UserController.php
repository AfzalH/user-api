<?php

namespace AfzalH\UserApi\Controllers;


use AfzalH\UserApi\Requests\StoreUser;

class UserController extends BaseController
{
    // route:post users
    public function store(StoreUser $request)
    {
        $user = $this->createUserFromRequest($request);
        return $this->buildCreationResponse($user);
    }

}
