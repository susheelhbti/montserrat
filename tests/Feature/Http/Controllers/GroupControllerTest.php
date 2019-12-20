<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\GroupController
 */
class GroupControllerTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('group.create'));

        $response->assertOk();
        $response->assertViewIs('groups.create');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $group = factory(\App\Group::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->delete(route('group.destroy', [$group]));

        $response->assertRedirect(action('GroupController@index'));
        $this->assertDeleted($group);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $group = factory(\App\Group::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('group.edit', [$group]));

        $response->assertOk();
        $response->assertViewIs('groups.edit');
        $response->assertViewHas('group');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('group.index'));

        $response->assertOk();
        $response->assertViewIs('groups.index');
        $response->assertViewHas('groups');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $group = factory(\App\Group::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('group.show', [$group]));

        $response->assertOk();
        $response->assertViewIs('groups.show');
        $response->assertViewHas('group');
        $response->assertViewHas('members');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->post(route('group.store'), [
            // TODO: send request data
        ]);

        $response->assertRedirect(action('GroupController@show', $group->id));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\GroupController::class,
            'store',
            \App\Http\Requests\StoreGroupRequest::class
        );
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $group = factory(\App\Group::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->put(route('group.update', [$group]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(intended('group/'.$group->id));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\GroupController::class,
            'update',
            \App\Http\Requests\UpdateGroupRequest::class
        );
    }

    // test cases...
}