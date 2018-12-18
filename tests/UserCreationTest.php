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
    public function userCreationMustNotPassWithEmptyName()
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
    public function userCreationMustNotPassWithDuplicateEmail()
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
    public function userCreationMustNotPassWithEmptyEmail()
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
    public function userCreationMustNotPassWithInvalidEmail()
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
    public function userCreationMustNotPassWithEmptyPassword()
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
    public function userCreationMustNotPassWithSmallPassword()
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
    public function aGuestUserCanNotCreateAnotherUser()
    {
        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'some.email@example.com',
            'password' => 'secret'
        ]);
        $r->assertStatus(401);
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
