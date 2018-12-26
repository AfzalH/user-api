<?php

namespace AfzalH\UserApi\Tests;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSetupTest extends Base
{
    /** @test */
    public function super_admin_can_create_initial_permissions_and_roles()
    {
        $this->becomeSuperAdmin();

        $r = $this->get(config('userApi.router_prefix') . 'init-permissions-and-roles');
        $r->assertOk();

        $this->assertEquals(Role::count(), count(config('userApi.initial_super_roles')) + count(config('userApi.initial_user_roles')));
        $this->assertEquals(Permission::count(), count(config('userApi.initial_super_permissions')) + count(config('userApi.initial_user_permissions')));
    }

    /** @test */
    public function someone_other_than_a_super_admin_user_can_not_create_initial_permissions_and_roles()
    {
        $r = $this->get(config('userApi.router_prefix') . 'init-permissions-and-roles');
        $r->assertStatus(401);

        $this->becomeARandomUser();

        $r = $this->get(config('userApi.router_prefix') . 'init-permissions-and-roles');
        $r->assertStatus(403);
    }


}
