<?php

namespace AfzalH\UserApi\Tests;

class SetupRelatedTest extends Base
{
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
