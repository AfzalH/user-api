<?php

namespace AfzalH\UserApi\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InitUserController extends Controller
{
    // route:get init-super-admin
    public function createInitialSuperAdminUser()
    {
        $this->checkIfAlreadyCreated();
        $user = $this->createUser();
        $this->assignRole($user);
        return $this->buildResponse($user);
    }

    public function checkIfAlreadyCreated(): void
    {
        $existing = User::whereEmail(config('userApi.initial_super_admin_email'))->first();
        if ($existing) {
            abort(422, 'Super admin already created');
        }
    }

    /**
     * @return User
     */
    public function createUser(): User
    {
        $user = new User();
        $this->populateFields($user);
        $user->save();
        return $user;
    }

    /**
     * @param User $user
     */
    public function assignRole(User $user): void
    {
        $this->createSuperAdminRolesAndPermission();
        $this->assignSuperAdminRole($user);
    }

    public function createSuperAdminRolesAndPermission()
    {
        /** @var Permission $permission */
        $permission = Permission::findOrCreate('manage users');
        /** @var Role $role */
        $role = Role::findOrCreate('super admin');
        $role->givePermissionTo($permission);
    }

    public function assignSuperAdminRole(User $user)
    {
        $user->assignRole('super admin');
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function buildResponse(User $user)
    {
        if ($user->id) {
            return response($user->id, 201);
        } else {
            return response('Error Creating User', 422);
        }
    }

    /**
     * @param User $user
     */
    public function populateFields(User $user): void
    {
        $user->email = config('userApi.initial_super_admin_email');
        $user->name = config('userApi.initial_super_admin_name');
        $user->password = bcrypt(config('userApi.initial_super_admin_password'));
    }

}
