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
    public function a_user_with_permission_can_add_another_user_to_his_business()
    {
        $actor = $this->becomeARandomUser();
        $member1 = $this->getAUser();
        $member2 = $this->getAUser();

        $this->post($this->prefix . 'businesses', ['name' => 'My Business']);

        $r = $this->post($this->prefix . 'business/add-member', ['business_id' => $actor->business->id, 'user' => $member1->email]);
        $r->assertStatus(201);
        $r = $this->post($this->prefix . 'business/add-member', ['business_id' => $actor->business->id, 'user' => $member2->id]);
        $r->assertStatus(201);

        $member1->refresh();
        $member2->refresh();
        $this->assertTrue($member1->isMemberOf($actor->business->id));
        $this->assertTrue($member2->isMemberOf($actor->business->id));
    }

    /** @test */
    public function guest_cannot_add_member()
    {
        $r = $this->post($this->prefix . 'business/add-member', ['business_id' => 1, 'user' => 1]);
        $r->assertStatus(401);
    }

    /** @test */
    public function user_without_permission_cannot_add_member()
    {
        $this->becomeARandomUser();
        $r = $this->post($this->prefix . 'business/add-member', ['business_id' => 1, 'user' => 1]);
        $r->assertStatus(403);
    }

    /** @test */
    public function user_cannot_add_user_to_another_business()
    {
        $actor1 = $this->becomeARandomUser();
        $this->post($this->prefix . 'businesses', ['name' => 'My Business']);
        $member1 = $this->getAUser();
        $r = $this->post($this->prefix . 'business/add-member', ['business_id' => $actor1->business->id, 'user' => $member1->email]);
        $r->assertStatus(201);

        $actor2 = $this->becomeARandomUser();
        $this->post($this->prefix . 'businesses', ['name' => 'My Business 2']);
        $member2 = $this->getAUser();
        $r = $this->post($this->prefix . 'business/add-member', ['business_id' => $actor2->business->id, 'user' => $member2->email]);
        $r->assertStatus(201);

        $r = $this->post($this->prefix . 'business/add-member', ['business_id' => $actor1->business->id, 'user' => $member2->email]);
        $r->assertStatus(403);

    }

    /** @test */
    public function cannot_add_a_member_who_is_already_a_member()
    {
        $actor1 = $this->becomeARandomUser();
        $this->post($this->prefix . 'businesses', ['name' => 'My Business']);
        $member1 = $this->getAUser();
        $r = $this->post($this->prefix . 'business/add-member', ['business_id' => $actor1->business->id, 'user' => $member1->email]);
        $r->assertStatus(201);

        $actor2 = $this->becomeARandomUser();
        $this->post($this->prefix . 'businesses', ['name' => 'My Business 2']);

        $r = $this->post($this->prefix . 'business/add-member', ['business_id' => $actor2->business->id, 'user' => $member1->email]);
        $r->assertStatus(422);
    }


    /** @test */
    public function a_guest_cannot_create_a_business()
    {
        $r = $this->post($this->prefix . 'businesses', ['name' => 'My Business']);
        $r->assertStatus(401);
    }

}
