<?php

namespace AfzalH\UserApi\Tests;


class BusinessCreationTest extends Base
{
    /** @test */
    public function a_user_can_create_a_business()
    {
        $actor = $this->becomeARandomUser();
        $r = $this->post($this->prefix . 'businesses', ['name' => 'My Business']);
        $r->assertStatus(201);
        $this->assertDatabaseHas('businesses', ['name' => 'My Business', 'owner_id' => $actor->id]);
    }

    /** @test */
    public function a_guest_cannot_create_a_business()
    {
        $r = $this->post($this->prefix . 'businesses', ['name' => 'My Business']);
        $r->assertStatus(401);
    }

}
