<?php

namespace AfzalH\UserApi\Controllers;

use AfzalH\UserApi\Requests\StoreUser;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BaseController extends Controller
{

    public function createUserFromRequest(StoreUser $request): User
    {
        $user = new User();
        $this->populateFields($request, $user);
        $user->save();
        return $user;
    }

    public function populateFields(StoreUser $request, User $user): void
    {
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->password = bcrypt($request->get('password'));
    }

    public function buildCreationResponse($object)
    {
        if ($object->id) {
            return response($object->id, 201);
        } else {
            return response('Error Creating Object', 422);
        }
    }

    public function getRoleFromRequest(Request $request)
    {
        $role_id_or_name = $request->get('role');
        /** @noinspection PhpParamsInspection */
        $role = is_numeric($role_id_or_name) ? Role::findById($role_id_or_name) : Role::findByName($role_id_or_name);
        return $role;
    }

    public function getPermissionFromRequest(Request $request)
    {
        $permission_id_or_name = $request->get('permission');
        /** @noinspection PhpParamsInspection */
        $role = is_numeric($permission_id_or_name) ? Permission::findById($permission_id_or_name) : Permission::findByName($permission_id_or_name);
        return $role;
    }
}
