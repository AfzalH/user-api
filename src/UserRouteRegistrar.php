<?php

namespace AfzalH\UserApi;

use Illuminate\Contracts\Routing\Registrar as Router;

class UserRouteRegistrar
{
    /**
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function all()
    {
        $this->testRoutes();
    }

    public function testRoutes(): void
    {
        $this->router->group(['middleware' => config('userApi.router_middleware_test')], function (Router $router) {
            $router->get('test-get-host', function () {
                return 'Server running on: ' . gethostname();
            });
        });
    }
}
