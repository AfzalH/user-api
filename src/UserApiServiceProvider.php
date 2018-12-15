<?php

namespace AfzalH\UserApi;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class UserApiServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/userApi.php' => config_path('userApi.php'),
            ], 'config');
        }
        $this->routes();
    }

    public function register()
    {
        $this->app->singleton('userapi', function () {
            return new UserApi();
        });
        $this->mergeConfigFrom(__DIR__ . '/../config/userApi.php', 'userApi');
    }

    public function routes(): void
    {
        $options = [
            'prefix' => config('userApi.router_prefix'),
            'namespace' => '\AfzalH\UserApi\Controllers'
        ];
        app('router')->group($options, function (Router $router) {
            $userRouter = new UserRouteRegistrar($router);
            $userRouter->all();
        });
    }
}
