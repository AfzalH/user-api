<?php


namespace AfzalH\UserApi\Tests;


use Laravel\Passport\Passport;

class RoleCRUDTest extends Base
{
    /** @test */
    public function super_admin_can_create_roles()
    {
        $super = $this->getAUserWithSuperManageUsersPermission();
        Passport::actingAs($super);
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

        Passport::actingAs($this->getAUser());
        $r = $this->post($this->prefix . 'roles', ['name' => 'HR Manager']);
        $r->assertStatus(403);
    }


}