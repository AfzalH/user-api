<?php

namespace AfzalH\UserAPI;

use Illuminate\Support\Facades\Facade;

/**
 * @see AfzalH\UserApi\UserApi
 */
class UserApiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'UserApi';
    }
}
