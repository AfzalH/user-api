<?php

namespace AfzalH\UserApi\Tests;

use App\User;

class SetupRelatedTest extends BaseTest
{
    /** @test */
    public function test_user_can_be_created()
    {
        $this->createRandomUsers(12);
        $count = User::count();
        $this->assertEquals(12, $count);
    }

    /** @test */
    public function admin_user_can_log_in()
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
    public function can_get_host_name()
    {
        $this->get(config('userApi.router_prefix') . 'test-get-host')->assertStatus(200);
    }

    /** @test */
    public function can_read_required_config()
    {
        $this->assertGreaterThan(1, strlen(config('userApi.initial_super_admin_email')));
        $this->assertGreaterThan(0, strlen(config('userApi.initial_super_admin_name')));
        $this->assertGreaterThan(1, strlen(config('userApi.initial_super_admin_password')));
        $this->assertGreaterThan(1, strlen(config('userApi.router_prefix')));
        $this->assertIsArray(config('userApi.router_middleware_test'));
    }
}
