<?php

namespace AfzalH\UserApi\Tests;

use App\User;
use Laravel\Passport\Passport;

class UserCreationTest extends BaseTest
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
        $user = $this->getUserWithManageUsersPermission();
        Passport::actingAs($user);

        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'some.email@example.com',
            'password' => 'secret'
        ]);
        $r->assertStatus(201);
    }

    /** @test */
    public function user_creation_must_not_pass_with_empty_name()
    {
        $user = $this->getUserWithManageUsersPermission();
        Passport::actingAs($user);

        $r = $this->post($this->prefix . 'users', [
            'name' => '',
            'email' => 'some.other.email@example.com',
            'password' => 'secret'
        ]);
        $r->assertStatus(422);


    }

    /** @test */
    public function user_creation_must_not_pass_with_duplicate_email()
    {
        $user = $this->getUserWithManageUsersPermission();
        Passport::actingAs($user);

        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'some.email@example.com',
            'password' => 'secret'
        ]);
        $r->assertStatus(201);

        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'some.email@example.com',
            'password' => 'secret'
        ]);
        $r->assertStatus(422);
    }

    /** @test */
    public function user_creation_must_not_pass_with_empty_email()
    {
        $user = $this->getUserWithManageUsersPermission();
        Passport::actingAs($user);

        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => '',
            'password' => 'secret'
        ]);
        $r->assertStatus(422);
    }

    /** @test */
    public function user_creation_must_not_pass_with_invalid_email()
    {
        $user = $this->getUserWithManageUsersPermission();
        Passport::actingAs($user);

        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'sdfsdf',
            'password' => 'secret'
        ]);
        $r->assertStatus(422);
    }

    /** @test */
    public function user_creation_must_not_pass_with_empty_password()
    {
        $user = $this->getUserWithManageUsersPermission();
        Passport::actingAs($user);

        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'sdfsdf@srizon.com',
            'password' => ''
        ]);
        $r->assertStatus(422);
    }

    /** @test */
    public function user_creation_must_not_pass_with_small_password()
    {
        $user = $this->getUserWithManageUsersPermission();
        Passport::actingAs($user);

        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'sdfsdf@srizon.com',
            'password' => 'abc'
        ]);
        $r->assertStatus(422);
    }

    /** @test */
    public function a_guest_user_can_not_create_another_user()
    {
        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'some.email@example.com',
            'password' => 'secret'
        ]);
        $r->assertStatus(401);
    }

    /** @test */
    public function a_user_without_proper_permission_can_not_create_another_user()
    {
        $user = $this->getAUser();
        Passport::actingAs($user);

        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'some.email@example.com',
            'password' => 'secret'
        ]);
        $r->assertStatus(403);
    }

}
