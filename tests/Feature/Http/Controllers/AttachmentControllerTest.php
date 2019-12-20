<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AttachmentController
 */
class AttachmentControllerTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * @test
     */
    public function delete_avatar_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('delete_avatar', ['user_id' => $attachment->user_id]));

        $response->assertRedirect(action('PersonController@show', $user_id));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function delete_contact_attachment_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('delete_contact_attachment', ['user_id' => $attachment->user_id, 'file_name' => $attachment->file_name]));

        $response->assertRedirect(action('PersonController@show', $user_id));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function delete_event_contract_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('delete_event_contract', ['event_id' => $attachment->event_id]));

        $response->assertRedirect(action('RetreatController@show', $event_id));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function delete_event_evaluations_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('delete_event_evaluations', ['event_id' => $attachment->event_id]));

        $response->assertRedirect(action('RetreatController@show', $event_id));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function delete_event_group_photo_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('delete_event_group_photo', ['event_id' => $attachment->event_id]));

        $response->assertRedirect(action('RetreatController@show', $event_id));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function delete_event_schedule_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('delete_event_schedule', ['event_id' => $attachment->event_id]));

        $response->assertRedirect(action('RetreatController@show', $event_id));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function get_avatar_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('get_avatar', ['user_id' => $attachment->user_id]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function get_event_contract_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('get_event_contract', ['event_id' => $attachment->event_id]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function get_event_evaluations_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('get_event_evaluations', ['event_id' => $attachment->event_id]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function get_event_group_photo_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('get_event_group_photo', ['event_id' => $attachment->event_id]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function get_event_schedule_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('get_event_schedule', ['event_id' => $attachment->event_id]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_contact_attachment_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $attachment = factory(\App\Attachment::class)->create();
        $attachment = factory(\App\Attachment::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('show_contact_attachment', ['user_id' => $attachment->user_id, 'file_name' => $attachment->file_name]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}