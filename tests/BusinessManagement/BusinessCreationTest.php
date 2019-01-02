<?php

namespace AfzalH\UserApi\Tests;


use AfzalH\UserApi\Controllers\InitPermissionController;

class BusinessCreationTest extends Base
{

    public function setUp()
    {
        parent::setUp();
        (new InitPermissionController())->createInitialRolesAndPermissions();
    }

    /** @test */
    public function a_user_can_create_a_business()
    {
        $actor = $this->becomeARandomUser();
        $r = $this->post($this->prefix . 'businesses', ['name' => 'My Business']);
        $r->assertStatus(201);
        $this->assertDatabaseHas('businesses', ['name' => 'My Business', 'owner_id' => $actor->id]);
        $this->assertNotNull($actor->business);
        $business = $actor->business;
        $this->assertNotNull($business->owner);
    }

    /** @test */
    public function cannot_create_a_business_with_invalid_name()
    {
        $this->becomeARandomUser();
        $r = $this->post($this->prefix . 'businesses', ['name' => '']);
        $r->assertStatus(422);
        $r = $this->post($this->prefix . 'businesses');
        $r->assertStatus(422);
    }


    /** @test */
    public function a_user_cannot_create_multiple_business()
    {
        $this->becomeARandomUser();
        $r = $this->post($this->prefix . 'businesses', ['name' => 'My Business']);
        $r->assertStatus(201);
        $r = $this->post($this->prefix . 'businesses', ['name' => 'My Business 2']);
        $r->assertStatus(403);
    }


    /** @test */
    public function a_guest_cannot_create_a_business()
    {
        $r = $this->post($this->prefix . 'businesses', ['name' => 'My Business']);
        $r->assertStatus(401);
    }

}
