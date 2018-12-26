<?php

namespace AfzalH\UserApi\Tests;

use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;

class RoleAssignmentTest extends Base
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function a_user_with_manage_users_permission_can_assign_a_role_by_role_id()
    {
        $this->becomeSuperUserManager();

        $user = $this->getAUser();
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
        $this->becomeSuperUserManager();

        $user = $this->getAUser();
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
        $randomUser = $this->getAUser();
        Passport::actingAs($randomUser);

        $user = $this->getAUser();
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
        $user = $this->getAUser();
        $role = Role::create(['name' => 'manager']);
        $r = $this->post($this->prefix . 'users/assign-role', [
            'user_id' => $user->id,
            'role' => $role->id
        ]);
        $r->assertStatus(401);

        $user->refresh();
        $this->assertFalse($user->hasRole($role->id));
    }

    /** @test */
    public function user_with_permission_can_remove_role()
    {
        $this->becomeSuperUserManager();

        $user = $this->getAUser();
        $role = Role::create(['name' => 'manager']);

        $r = $this->post($this->prefix . 'users/assign-role', [
            'user_id' => $user->id,
            'role' => $role->id
        ]);
        $r->assertStatus(202);

        $user->refresh();

        $this->assertTrue($user->hasRole($role));

        $r = $this->post($this->prefix . 'users/remove-role', [
            'user_id' => $user->id,
            'role' => $role->id
        ]);
        $r->assertStatus(202);

        $user->refresh();
        $this->assertFalse($user->hasRole($role));

    }


}
