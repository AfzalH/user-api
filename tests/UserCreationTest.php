<?php

namespace AfzalH\UserApi\Tests;

use App\User;
use Laravel\Passport\Passport;

class UserCreationTest extends Base
{
    public $superEmail;

    public function setUp()
    {
        parent::setUp();
        $this->superEmail = config('userApi.initial_super_admin_email');
    }

    /** @test
     * @throws \Exception
     */
    public function initial_super_admin_can_be_created_and_has_super_admin_roles_and_super_user_management_permission()
    {
        $r = $this->get($this->prefix . 'init-super-admin');
        $r->assertStatus(201);

        $super = User::whereEmail($this->superEmail)->firstOrFail();
        $this->assertTrue($super->hasRole('super admin'));
        $this->assertTrue($super->hasPermissionTo('super manage users'));
    }

    /** @test */
    public function initial_super_admin_can_not_be_created_more_than_once()
    {
        $this->get($this->prefix . 'init-super-admin')->assertStatus(201);
        $this->get($this->prefix . 'init-super-admin')->assertStatus(422);
    }

    /** @test */
    public function a_user_with_necessary_permission_can_create_another_user()
    {
        $this->becomeSuperUserManager();

        $r = $this->createSampleUserViaRestAPI();
        $r->assertStatus(201);
    }

    /** @test */
    public function a_guest_user_can_not_create_another_user()
    {
        $r = $this->createSampleUserViaRestAPI();
        $r->assertStatus(401);
    }

    /** @test */
    public function a_user_without_proper_permission_can_not_create_another_user()
    {
        $this->becomeARandomUser();

        $r = $this->createSampleUserViaRestAPI();
        $r->assertStatus(403);
    }

}
