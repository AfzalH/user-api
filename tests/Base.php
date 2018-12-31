<?php

namespace AfzalH\UserApi\Tests;

use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\CreatesApplication;

class Base extends TestCase
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

    public function getAUserWithSuperManageUsersPermission(): User
    {
        $user = $this->getAUser();
        $permission = Permission::findOrCreate('super manage users');
        $user->givePermissionTo($permission);
        return $user;
    }

    public function getASuperAdmin(): User
    {
        $user = $this->getAUser();
        $role = Role::findOrCreate('super admin');
        $user->assignRole($role);
        return $user;
    }

    protected function getAUser($params = []): User
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

    public function createSampleUserViaRestAPI(): \Illuminate\Foundation\Testing\TestResponse
    {
        $r = $this->post($this->prefix . 'users', [
            'name' => 'Some Name',
            'email' => 'some.email@example.com',
            'password' => 'secret'
        ]);
        return $r;
    }

    public function becomeSuperUserManager(): User
    {
        $user = $this->getAUserWithSuperManageUsersPermission();
        Passport::actingAs($user);
        return $user;
    }

    public function becomeARandomUser(): User
    {
        $user = $this->getAUser();
        Passport::actingAs($user);
        return $user;
    }

    public function becomeSuperAdmin(): User
    {
        $user = $this->getASuperAdmin();
        Passport::actingAs($user);
        return $user;
    }
}
