<?php

namespace AfzalH\UserApi\Tests;


use Laravel\Passport\Passport;
use Spatie\Permission\Models\Permission;

class PermissionAssignmentTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test
     * @throws \Exception
     */
    public function a_user_with_manage_users_permission_can_assign_a_permission_by_permission_name()
    {
        $admin = $this->getUserWithSuperManageUsersPermission();
        Passport::actingAs($admin);

        $user = $this->getAUser();
        $permission = Permission::create(['name' => 'create user']);
        $permission->refresh();
        $r = $this->post($this->prefix . 'users/assign-permission', [
            'user_id' => $user->id,
            'permission' => $permission->name
        ]);
        $r->assertStatus(202);

        $user->refresh();
        $this->assertTrue($user->hasPermissionTo($permission->name));
    }

    /** @test
     * @throws \Exception
     */
    public function a_user_with_manage_users_permission_can_assign_a_permission_by_permission_id()
    {
        $admin = $this->getUserWithSuperManageUsersPermission();
        Passport::actingAs($admin);

        $user = $this->getAUser();
        $permission = Permission::create(['name' => 'create user']);
        $permission->refresh();
        $r = $this->post($this->prefix . 'users/assign-permission', [
            'user_id' => $user->id,
            'permission' => $permission->id
        ]);
        $r->assertStatus(202);

        $user->refresh();
        $this->assertTrue($user->hasPermissionTo($permission->name));
    }

    /** @test
     * @throws \Exception
     */
    public function a_user_without_manage_users_permission_cannot_assign_a_permission_by_permission_name()
    {
        $actor = $this->getAUser();
        Passport::actingAs($actor);

        $user = $this->getAUser();
        $permission = Permission::create(['name' => 'create user']);
        $permission->refresh();
        $r = $this->post($this->prefix . 'users/assign-permission', [
            'user_id' => $user->id,
            'permission' => $permission->id
        ]);
        $r->assertStatus(403);

        $user->refresh();
        $this->assertFalse($user->hasPermissionTo($permission->name));
    }

    /** @test
     * @throws \Exception
     */
    public function a_guest_cannot_assign_a_permission_by_permission_name()
    {
        $user = $this->getAUser();
        $permission = Permission::create(['name' => 'create user']);
        $permission->refresh();
        $r = $this->post($this->prefix . 'users/assign-permission', [
            'user_id' => $user->id,
            'permission' => $permission->id
        ]);
        $r->assertStatus(401);

        $user->refresh();
        $this->assertFalse($user->hasPermissionTo($permission->name));
    }

    /** @test
     * @throws \Exception
     */
    public function user_with_permission_can_revoke_permission()
    {
        $admin = $this->getUserWithSuperManageUsersPermission();
        Passport::actingAs($admin);

        $user = $this->getAUser();
        $permission = Permission::create(['name' => 'manage all']);

        $r = $this->post($this->prefix . 'users/assign-permission', [
            'user_id' => $user->id,
            'permission' => $permission->id
        ]);
        $r->assertStatus(202);

        $user->refresh();

        $this->assertTrue($user->hasPermissionTo($permission));

        $r = $this->post($this->prefix . 'users/revoke-permission', [
            'user_id' => $user->id,
            'permission' => $permission->id
        ]);
        $r->assertStatus(202);

        $user->refresh();
        $this->assertFalse($user->hasPermissionTo($permission));

    }
}
