<?php

namespace AfzalH\UserApi\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends BaseController
{
    // route:post roles
    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->get('name')]);
        return $this->buildCreationResponse($role);
    }

    // route:post roles/assign-permission
    public function assignPermission(Request $request)
    {
        $role = $this->getRoleFromRequest($request);
        $permission = $this->getPermissionFromRequest($request);
        $role->givePermissionTo($permission);
        return response($role->id, 202);
    }
    // route:post roles/revoke-permission
    public function revokePermission(Request $request)
    {
        $role = $this->getRoleFromRequest($request);
        $permission = $this->getPermissionFromRequest($request);
        $role->revokePermissionTo($permission);
        return response($role->id, 202);
    }

}
