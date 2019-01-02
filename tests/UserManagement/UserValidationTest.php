<?php

namespace AfzalH\UserApi\Tests;

class UserValidationTest extends Base
{
    public $superEmail;

    public function setUp()
    {
        parent::setUp();
        $this->superEmail = config('userApi.initial_super_admin_email');
    }

    /** @test */
    public function user_creation_must_not_pass_with_empty_name()
    {
        $this->becomeSuperUserManager();

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
        $this->becomeSuperUserManager();

        $r = $this->createSampleUserViaRestAPI();
        $r->assertStatus(201);

        $r = $this->createSampleUserViaRestAPI();
        $r->assertStatus(422);
    }

    /** @test */
    public function user_creation_must_not_pass_with_empty_email()
    {
        $this->becomeSuperUserManager();

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
        $this->becomeSuperUserManager();

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
        $this->becomeSuperUserManager();

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
        $this->becomeSuperUserManager();

        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'sdfsdf@srizon.com',
            'password' => 'abc'
        ]);
        $r->assertStatus(422);
    }

}
