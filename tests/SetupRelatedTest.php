<?php

namespace AfzalH\UserApi\Tests;

use App\User;

class SetupRelatedTest extends BaseTest
{
    /** @test */
    public function passportRoutesExist()
    {
        $this->artisan('passport:install');
        $this->post('oauth/token')->assertStatus(400);
    }

    /** @test */
    public function testUserCanBeCreated()
    {
        $this->createRandomUsers(12);
        $count = User::count();
        $this->assertEquals(12, $count);
    }

    /** @test */
    public function adminUserCanLogIn()
    {
        $this->artisan('passport:install');
        $this->createAdminUser();
        $res = $this->post('oauth/token', [
            'grant_type' => 'password',
            'username' => 'afzal.csedu@gmail.com',
            'password' => 'secret'
        ]);
        $res->assertStatus(200);
    }

    /** @test */
    public function adminUserCanNotLogInWithWrongPassword()
    {
        $this->artisan('passport:install');
        $this->createAdminUser();
        $this->post('oauth/token', [
            'grant_type' => 'password',
            'username' => 'afzal.csedu@gmail.com',
            'password' => 'wrongPassword'
        ])->assertStatus(401);
    }
}
