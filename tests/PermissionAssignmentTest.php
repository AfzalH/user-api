<?php

namespace AfzalH\UserApi\Tests;


use Laravel\Passport\Passport;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionAssignmentTest extends Base
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
        $this->becomeSuperUserManager();
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
    public function a_user_with_manage_users_permission_can_assign_a_permission_to_a_user_by_permission_id()
    {
        $this->becomeSuperUserManager();

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
    public function a_user_without_manage_users_permission_cannot_assign_a_permission_to_a_user_by_permission_name()
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
    public function user_with_permission_can_revoke_permission_from_a_user()
    {
        $this->becomeSuperUserManager();

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

    /** @test
     * @throws \Exception
     */
    public function a_user_with_manage_users_permission_can_assign_a_permission_to_a_role()
    {
        $this->becomeSuperUserManager();

        $role = Role::create(['name' => 'manager']);
        $permission = Permission::create(['name' => 'manage things']);

        $r = $this->post($this->prefix . 'roles/assign-permission', [
            'role' => $role->id,
            'permission' => $permission->id
        ]);
        $r->assertStatus(202);

        $role->refresh();
        $this->assertTrue($role->hasPermissionTo($permission->name));

        $user = $this->getAUser();
        $user->assignRole($role);
        $user->refresh();

        $this->assertTrue($user->hasPermissionTo('manage things'));
    }

    /** @test
     * @throws \Exception
     */
    public function a_user_with_manage_users_permission_can_revoke_a_permission_from_a_role()
    {
        $this->a_user_with_manage_users_permission_can_assign_a_permission_to_a_role();
        /** @var Role $role */
        $role = Role::findOrCreate('manager');
        /** @var Permission $permission */
        $permission = Permission::findOrCreate('manage things');
        $user = $this->getAUser();
        $user->assignRole('manager');
        $user->refresh();
        $this->assertTrue($user->hasPermissionTo('manage things'));

        $r = $this->post($this->prefix . 'roles/revoke-permission', [
            'role' => $role->id,
            'permission' => $permission->id
        ]);

        $r->assertStatus(202);

        $user->refresh();
        $this->assertFalse($user->hasPermissionTo('manage things'));
        $role->refresh();
        $this->assertFalse($role->hasPermissionTo('manage things'));

    }
}
