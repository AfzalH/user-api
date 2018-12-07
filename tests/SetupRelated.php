<?php

namespace AfzalH\UserApi\Tests;

class SetupRelated extends BaseTest
{
    /** @test */
    public function passport_routes_exist()
    {
        \Artisan::call('passport:install');
        $this->post('oauth/token')->assertStatus(400);
    }
}
