<?php

namespace AfzalH\UserApi;

use Illuminate\Contracts\Routing\Registrar as Router;

class UserRouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function all()
    {
        $this->router->group(['middleware' => ['api']], function (Router $router) {
            $router->get('user-test', function () {
                return 'Server running on: ' . gethostname();
            });
        });
    }
}
