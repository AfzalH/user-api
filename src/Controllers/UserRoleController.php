<?php

namespace AfzalH\UserApi\Controllers;

use App\User;
use Illuminate\Http\Request;
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
        if (is_numeric($role_id_or_name)) {
            $role = Role::findById($role_id_or_name);
        } else {
            $role = Role::findByName($role_id_or_name);
        }
        return $role;
    }
}
