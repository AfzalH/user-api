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
    public function initialSuperAdminCanBeCreatedAndHasSuperAdminRolesAndUserManagementPermission()
    {
        $r = $this->get($this->prefix . 'init-super-admin');
        $r->assertStatus(201);

        $super = User::whereEmail($this->superEmail)->firstOrFail();
        $this->assertTrue($super->hasRole('super admin'));
        $this->assertTrue($super->hasPermissionTo('manage users'));
    }

    /** @test */
    public function initialSuperAdminCanNotBeCreatedMoreThanOnce()
    {
        $this->get($this->prefix . 'init-super-admin')->assertStatus(201);
        $this->get($this->prefix . 'init-super-admin')->assertStatus(422);
    }

    /** @test */
    public function aUserWithNecessaryPermissionCanCreateAnotherUser()
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
    public function aUserWithoutAuthenticationCanNotCreateAnotherUser()
    {
        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'some.email@example.com',
            'password' => 'secret'
        ]);
        $r->assertStatus(403);
    }

    /** @test */
    public function aUserWithoutProperPermissionCanNotCreateAnotherUser()
    {
        $user = $this->createAUser();
        Passport::actingAs($user);

        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'some.email@example.com',
            'password' => 'secret'
        ]);
        $r->assertStatus(403);
    }

}
