<?php
return [
    'router_prefix' => 'api/v1/',
    'router_middleware_test' => ['api'],
    'router_middleware_setup' => ['api'],
    'router_middleware_setup_permission' => ['api', 'auth:api', 'role:super admin'],
    'router_middleware_user' => ['api', 'auth:api', 'permission:manage users|super manage users'],
    'router_middleware_business_creation' => ['api', 'auth:api'],
    'router_middleware_business_manage_members' => ['api', 'auth:api', 'role:super admin|admin'],
    'initial_super_admin_email' => 'afzal@srizon.com',
    'initial_super_admin_name' => 'Afzal Hossain',
    'initial_super_admin_password' => 'superSecret',
    'initial_super_permissions' =>
        [
        ],
    'initial_user_permissions' =>
        [
        ],
    'initial_super_roles' =>
        [
            [
                'name' => 'super admin',
                'permissions' => [
                    'super manage users',
                    'super create users',
                    'super read users',
                    'super update users',
                    'super delete users',
                    'super manage roles',
                    'super manage permissions',
                    'super businesses',
                ]
            ],
            'super manager',
            'super other'
        ],
    'initial_user_roles' =>
        [
            [
                'name' => 'admin',
                'permissions' => [
                    'manage users',
                    'create users',
                    'read users',
                    'update users',
                    'delete users',
                    'manage roles',
                    'manage permissions',
                ]
            ],
            [
                'name' => 'admin',
                'permissions' => [
                    'manage users',
                    'create users',
                    'read users',
                    'update users',
                    'delete users',
                    'delete admins',
                    'delete business',
                    'manage roles',
                    'manage permissions',
                ]
            ],
            'manager',
            'registered'
        ],
];
