<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RoleController
 */
class RoleControllerTest extends TestCase
{

    use WithFaker;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-role');

        $response = $this->actingAs($user)->get(route('role.create'));

        $response->assertOk();
        $response->assertViewIs('admin.roles.create');
        $response->assertSeeText('Create Role');
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('delete-role');
        $role = factory(\App\Role::class)->create();

        $response = $this->actingAs($user)->delete(route('role.destroy', [$role]));

        $response->assertRedirect(action('RoleController@index'));
        $this->assertSoftDeleted($role);
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('update-role');
        $role = factory(\App\Role::class)->create();

        $response = $this->actingAs($user)->get(route('role.edit', [$role]));

        $response->assertOk();
        $response->assertViewIs('admin.roles.edit');
        $response->assertViewHas('role');
        $response->assertSeeText('Edit');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-role');

        $response = $this->actingAs($user)->get(route('role.index'));

        $response->assertOk();
        $response->assertViewIs('admin.roles.index');
        $response->assertViewHas('roles');
        $response->assertSeeText('Roles');
}

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-role');
        $role = factory(\App\Role::class)->create();

        $response = $this->actingAs($user)->get(route('role.show', [$role]));

        $response->assertOk();
        $response->assertViewIs('admin.roles.show');
        $response->assertViewHas('role');
        $response->assertViewHas('permissions');
        $response->assertViewHas('users');
        $response->assertSeeText('Role details');
        $response->assertSeeText(e($role->description));

    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-role');

        $new_name = $this->faker->jobTitle;
        $new_description = $this->faker->sentence;

        $response = $this->actingAs($user)->post(route('role.store'), [
          'name' => $new_name,
          'display_name' => $new_name,
          'description' => $new_description,
        ]);

        $response->assertRedirect(action('RoleController@index'));
        $this->assertDatabaseHas('roles', [
          'name' => $new_name,
          'display_name' => $new_name,
          'description' => $new_description,
        ]);

    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('update-role');
        $role = factory(\App\Role::class)->create();

        $original_description = $role->description;
        $new_name = $this->faker->jobTitle;
        $new_description = $this->faker->sentence;

        $response = $this->actingAs($user)->put(route('role.update', [$role]), [
            'id'  => $role->id,
            'name' => $new_name,
            'display_name' => $new_name,
            'description' => $new_description,
        ]);

        $updated = \App\Role::findOrFail($role->id);

        $response->assertRedirect(action('RoleController@index'));
        $this->assertEquals($updated->description, $new_description);
        $this->assertNotEquals($updated->description, $original_description);
    }

    /**
     * @test
     */
    public function update_permissions_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('update-role');
        $role = factory(\App\Role::class)->create();
        $permission = factory(\App\Permission::class)->create();

        $response = $this->actingAs($user)->post(route('admin.role.update_permissions'), [
            'id' => $role->id,
            'permissions' => array($permission->id),
        ]);

        $response->assertRedirect(action('RoleController@index'));
        $this->assertDatabaseHas('permission_role', [
          'role_id' => $role->id,
          'permission_id' => $permission->id,
          'deleted_at' => null,
        ]);


    }

    /**
     * @test
     */
    public function update_users_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('update-role');
        $role = factory(\App\Role::class)->create();

        $response = $this->actingAs($user)->post(route('admin.role.update_users'), [
            'id' => $role->id,
            'users' => array($user->id),
        ]);

        $response->assertRedirect(action('RoleController@index'));
        $this->assertDatabaseHas('role_user', [
          'role_id' => $role->id,
          'user_id' => $user->id,
          'deleted_at' => null,
        ]);

        // TODO: perform additional assertions
    }

    // test cases...
}
