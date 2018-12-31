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
        $this->createThesePermissions($permissions);
    }

    public function createUserPermissions(): void
    {
        $permissions = config('userApi.initial_user_permissions');
        $this->createThesePermissions($permissions);
    }

    public function createSuperRoles(): void
    {
        $roles = config('userApi.initial_super_roles');
        $this->createTheseRoles($roles);
    }

    public function createUserRoles(): void
    {
        $roles = config('userApi.initial_user_roles');
        $this->createTheseRoles($roles);
    }

    public function createTheseRoles($roles): void
    {
        foreach ($roles as $role) {
            if (is_array($role)) {
                /** @var Role $createdRole */
                $createdRole = Role::findOrCreate($role['name']);
                $this->createThesePermissions($role['permissions']);
                $this->assignThesePermissions($role['permissions'], $createdRole);
            } else {
                Role::findOrCreate($role);
            }

        }
    }

    public function createThesePermissions($permissions): void
    {
        if (!is_array($permissions)) return;
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }

    public function assignThesePermissions($permissions, Role $createdRole): void
    {
        if (!is_array($permissions)) return;
        foreach ($permissions as $permission) {
            $createdRole->givePermissionTo($permission);
        }
    }
}
