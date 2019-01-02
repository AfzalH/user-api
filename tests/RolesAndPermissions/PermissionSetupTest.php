<?php

namespace AfzalH\UserApi\Tests;

class PermissionSetupTest extends Base
{
    /** @test
     * @throws \Exception
     */
    public function super_admin_can_create_initial_permissions_and_roles()
    {
        $this->becomeSuperAdmin();

        $r = $this->get(config('userApi.router_prefix') . 'init-permissions-and-roles');
        $r->assertOk();

        $super = $this->getASuperAdmin();
        $this->assertTrue($super->hasPermissionTo('super businesses'));
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
