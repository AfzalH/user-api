<?php

namespace AfzalH\UserApi\Controllers;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function createInitialSuperAdminUser()
    {
        $existing = User::whereEmail(config('userApi.initial_super_admin_email'))->first();
        if ($existing) {
            abort(422, 'User already exists');
        }
        $user = new User();
        $user->email = config('userApi.initial_super_admin_email');
        $user->name = config('userApi.initial_super_admin_name');
        $user->password = config('userApi.initial_super_admin_password');
        $id = $user->save();
        $this->createSuperAdminRolesAndPermission();
        $this->assignSuperAdminRole($user);
        if ($id) {
            return response($user->id, 201);
        } else {
            return response('Error Creating User', 422);
        }
    }

    public function createSuperAdminRolesAndPermission()
    {
        /** @var Permission $permission */
        $permission = Permission::create(['name' => 'manage users']);
        /** @var Role $role */
        $role = Role::create(['name' => 'super admin']);
        $role->givePermissionTo($permission);
    }

    public function assignSuperAdminRole(User $user)
    {
        $user->assignRole('super admin');
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->password = bcrypt($request->get('password'));
        $id = $user->save();
        if ($id) {
            return response($user->id, 201);
        } else {
            return response('Error Creating User', 422);
        }
    }

    public function assignRole(Request $request)
    {
        $user = User::findOrFail($request->get('user_id'));
        $role = $this->getRoleFromRequest($request);

        $user->assignRole($role);

        return response($user->id, 202);
    }

    /**
     * @param Request $request
     * @return \Spatie\Permission\Contracts\Role|Role
     */
    public function getRoleFromRequest(Request $request)
    {
        $role_id_or_name = $request->get('role');
        if (is_numeric($role_id_or_name)) {
            $role = Role::findById($role_id_or_name);
        } else {
            $role = Role::findByName($role_id_or_name);
        }
        return $role;
    }
}
