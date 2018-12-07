<?php

namespace AfzalH\UserApi\Tests;

use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class BaseTest extends TestCase
{
    use CreatesApplication, RefreshDatabase;
    protected $passportInstalled = false;

    public function setUp()
    {
        parent::setUp();
        $this->defaultHeaders['X-Requested-With'] = 'XMLHttpRequest';
        $this->defaultHeaders['accept'] = 'application/json, text/plain, */*';
    }

    public function createUsers()
    {
        $this->createAdminUser();
        $this->createRandomUsers();
    }

    protected function createAdminUser()
    {
        User::create([
            'name' => 'Afzal Hossain',
            'email' => 'afzal.csedu@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ]);
    }

    protected function createRandomUsers($count = 10)
    {
        $faker = Factory::create();
        while ($count--) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'remember_token' => str_random(10),
            ]);
        }
    }

    /** @test */
    public function suppressWarning()
    {
        $this->assertTrue(true);
    }
}
