<?php
/**
 * Created by PhpStorm.
 * User: Afzal
 * Date: 2018-12-16
 * Time: 12:20
 */

namespace AfzalH\UserApi\Tests;


use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;

class UserPermissionTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function a_user_with_manage_users_permission_can_assign_a_role_by_role_id()
    {
        $admin = $this->getUserWithManageUsersPermission();
        Passport::actingAs($admin);

        $user = $this->createAUser();
        $role = Role::create(['name' => 'manager']);
        $r = $this->post($this->prefix . 'users/assign-role', [
            'user_id' => $user->id,
            'role' => $role->id
        ]);
        $r->assertStatus(202);

        $user->refresh();
        $this->assertTrue($user->hasRole($role->id));
    }

    /** @test */
    public function a_user_with_manage_users_permission_can_assign_a_role_by_role_name()
    {
        $admin = $this->getUserWithManageUsersPermission();
        Passport::actingAs($admin);

        $user = $this->createAUser();
        $role = Role::create(['name' => 'manager']);
        $r = $this->post($this->prefix . 'users/assign-role', [
            'user_id' => $user->id,
            'role' => $role->name
        ]);
        $r->assertStatus(202);

        $user->refresh();
        $this->assertTrue($user->hasRole($role->name));
    }

    /** @test */
    public function a_user_without_manage_users_permission_can_not_assign_a_role()
    {
        $randomUser = $this->createAUser();
        Passport::actingAs($randomUser);

        $user = $this->createAUser();
        $role = Role::create(['name' => 'manager']);
        $r = $this->post($this->prefix . 'users/assign-role', [
            'user_id' => $user->id,
            'role' => $role->id
        ]);
        $r->assertStatus(403);

        $user->refresh();
        $this->assertFalse($user->hasRole($role->id));
    }

    /** @test */
    public function a_guest_can_not_assign_a_role()
    {
        $user = $this->createAUser();
        $role = Role::create(['name' => 'manager']);
        $r = $this->post($this->prefix . 'users/assign-role', [
            'user_id' => $user->id,
            'role' => $role->id
        ]);
        $r->assertStatus(401);

        $user->refresh();
        $this->assertFalse($user->hasRole($role->id));
    }
}
