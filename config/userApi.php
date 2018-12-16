<?php
return [
    'router_prefix' => 'api/v1/',
    'router_middleware_test' => ['api'],
    'router_middleware_setup' => ['api'],
    'router_middleware_user' => ['api', 'auth:api', 'permission:manage users'],
    'initial_super_admin_email' => 'afzal@srizon.com',
    'initial_super_admin_name' => 'Afzal Hossain',
    'initial_super_admin_password' => 'superSecret',
];
