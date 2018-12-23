<?php

namespace AfzalH\UserApi\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleController extends BaseController
{
    // route:post users/assign-role
    public function assignRole(Request $request)
    {
        $user = User::findOrFail($request->get('user_id'));
        $role = $this->getRoleFromRequest($request);

        $user->assignRole($role);

        return response($user->id, 202);
    }

    public function getRoleFromRequest(Request $request)
    {
        $role_id_or_name = $request->get('role');
        /** @noinspection PhpParamsInspection */
        $role = is_numeric($role_id_or_name) ? Role::findById($role_id_or_name) : Role::findByName($role_id_or_name);
        return $role;
    }

    public function assignPermission(Request $request)
    {
        $user = User::findOrFail($request->get('user_id'));
        $permission = $this->getPermissionFromRequest($request);

        $user->givePermissionTo($permission);

        return response($user->id, 202);
    }

    public function getPermissionFromRequest(Request $request)
    {
        $permission_id_or_name = $request->get('permission');
        /** @noinspection PhpParamsInspection */
        $role = is_numeric($permission_id_or_name) ? Permission::findById($permission_id_or_name) : Permission::findByName($permission_id_or_name);
        return $role;
    }

    // route:post users/remove-role
    public function removeRole(Request $request)
    {
        $user = User::findOrFail($request->get('user_id'));
        $role = $this->getRoleFromRequest($request);

        $user->removeRole($role);

        return response($user->id, 202);
    }

    // route:post users/revoke-permission
    public function revokePermission(Request $request)
    {
        $user = User::findOrFail($request->get('user_id'));
        $permission = $this->getPermissionFromRequest($request);

        $user->revokePermissionTo($permission);

        return response($user->id, 202);
    }
}
