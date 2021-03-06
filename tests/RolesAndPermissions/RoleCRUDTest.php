<?php


namespace AfzalH\UserApi\Tests;


class RoleCRUDTest extends Base
{
    /** @test */
    public function super_admin_can_create_roles()
    {
        $this->becomeSuperUserManager();
        $r = $this->post($this->prefix . 'roles', ['name' => 'HR Manager']);
        $r->assertStatus(201);
        // duplicate should produce error
        $r = $this->post($this->prefix . 'roles', ['name' => 'HR Manager']);
        $r->assertStatus(500);

    }

    /** @test */
    public function guestOrUnauthorizedUserCannotCrateRoles()
    {
        $r = $this->post($this->prefix . 'roles', ['name' => 'HR Manager']);
        $r->assertStatus(401);

        $this->becomeARandomUser();
        $r = $this->post($this->prefix . 'roles', ['name' => 'HR Manager']);
        $r->assertStatus(403);
    }

    /** @test */
    public function super_admin_or_admin_can_delete_roles()
    {
        $this->becomeSuperUserManager();
        $r = $this->post($this->prefix . 'roles', ['name' => 'HR Manager']);
        $r->assertStatus(201);

        $r = $this->delete($this->prefix . 'roles', ['role' => 'HR Manager']);
        $r->assertStatus(200);

    }


}
