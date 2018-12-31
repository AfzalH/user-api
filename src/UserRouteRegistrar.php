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
        $this->setupRoutes();
        $this->userRoutes();
        $this->businessRoutes();
    }

    public function testRoutes(): void
    {
        $this->router->group(['middleware' => config('userApi.router_middleware_test')], function (Router $router) {
            $router->get('test-get-host', function () {
                return 'Server running on: ' . gethostname();
            });
        });
    }

    public function setupRoutes()
    {
        $this->router->group(['middleware' => config('userApi.router_middleware_setup')], function (Router $router) {
            $router->get('init-super-admin', 'InitUserController@createInitialSuperAdminUser');
        });
        $this->router->group(['middleware' => config('userApi.router_middleware_setup_permission')], function (Router $router) {
            $router->get('init-permissions-and-roles', 'InitPermissionController@createInitialRolesAndPermissions');
        });
    }

    public function userRoutes()
    {
        $this->router->group(['middleware' => config('userApi.router_middleware_user')], function (Router $router) {

            $router->post('users/assign-permission', 'UserRoleController@assignPermission');
            $router->post('users/revoke-permission', 'UserRoleController@revokePermission');
            $router->post('users/assign-role', 'UserRoleController@assignRole');
            $router->post('users/remove-role', 'UserRoleController@removeRole');
            $router->post('users', 'UserController@store');

            $router->post('roles/assign-permission', 'RoleController@assignPermission');
            $router->post('roles/revoke-permission', 'RoleController@revokePermission');
            $router->post('roles', 'RoleController@store');

            $router->delete('roles', 'RoleController@delete');

        });
    }

    public function businessRoutes()
    {
        $this->router->group(['middleware' => config('userApi.router_middleware_business_creation')], function (Router $router) {
            $router->post('businesses', 'BusinessController@store');
        });
        $this->router->group(['middleware' => config('userApi.router_middleware_business_manage_members')], function (Router $router) {
            $router->post('business/add-member', 'BusinessController@addMember');
        });
    }
}
