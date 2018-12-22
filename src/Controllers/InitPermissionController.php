<?php

namespace AfzalH\UserApi\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InitPermissionController
{
    // route:get init-permissions-and-roles
    public function createInitialRolesAndPermissions()
    {
        $this->createPermissions();
        $this->createRoles();
    }

    public function createPermissions(): void
    {
        $this->createSuperPermissions();
        $this->createUserPermissions();
    }

    public function createRoles(): void
    {
        $this->createSuperRoles();
        $this->createUserRoles();
    }

    public function createSuperPermissions(): void
    {
        $permissions = config('userApi.initial_super_permissions');
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }

    public function createUserPermissions(): void
    {
        $permissions = config('userApi.initial_user_permissions');
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }

    public function createSuperRoles(): void
    {
        $roles = config('userApi.initial_super_roles');
        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }
    }

    public function createUserRoles(): void
    {
        $roles = config('userApi.initial_user_roles');
        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }
    }
}
