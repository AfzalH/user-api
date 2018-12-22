<?php

namespace AfzalH\UserApi\Tests;

use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\CreatesApplication;

class BaseTest extends TestCase
{
    use CreatesApplication, RefreshDatabase;
    protected $passportInstalled = false;
    public $prefix;

    public function setUp()
    {
        parent::setUp();
        $this->prefix = config('userApi.router_prefix');

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
                'email' => $faker->email,
                'email_verified_at' => now(),
                'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'remember_token' => str_random(10),
            ]);
        }
    }

    public function getUserWithManageUsersPermission(): User
    {
        $user = $this->getAUser();
        $permission = Permission::create(['name' => 'manage users']);
        $user->givePermissionTo($permission);
        return $user;
    }

    public function getASuperAdmin(): User
    {
        $user = $this->getAUser();
        $role = Role::create(['name' => 'super admin']);
        $user->assignRole($role);
        return $user;
    }

    /**
     * @param array $params
     * @return User
     */
    protected function getAUser($params = [])
    {
        $faker = Factory::create();
        $user = User::create([
            'name' => $params['name'] ?? $faker->name,
            'email' => $params['email'] ?? $faker->email,
            'email_verified_at' => now(),
            'password' => isset($params['password']) ? bcrypt($params['password']) : '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ]);
        return $user;
    }

    /** @test */
    public function suppress_warning()
    {
        $this->assertTrue(true);
    }
}
