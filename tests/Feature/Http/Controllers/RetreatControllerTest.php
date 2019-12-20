<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RetreatController
 */
class RetreatControllerTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * @test
     */
    public function assign_rooms_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('retreat.assign_rooms', ['id' => $retreat->id]));

        $response->assertOk();
        $response->assertViewIs('retreats.assign_rooms');
        $response->assertViewHas('retreat');
        $response->assertViewHas('registrations');
        $response->assertViewHas('rooms');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function calendar_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('calendar'));

        $response->assertOk();
        $response->assertViewIs('calendar.index');
        $response->assertViewHas('calendar_events');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function checkin_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('retreat.checkin', ['id' => $retreat->id]));

        $response->assertRedirect(back());

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function checkout_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('retreat.checkout', ['id' => $retreat->id]));

        $response->assertRedirect(back());

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('retreat.create'));

        $response->assertOk();
        $response->assertViewIs('retreats.create');
        $response->assertViewHas('d');
        $response->assertViewHas('i');
        $response->assertViewHas('a');
        $response->assertViewHas('c');
        $response->assertViewHas('event_types');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->delete(route('retreat.destroy', [$retreat]));

        $response->assertRedirect(action('RetreatController@index'));
        $this->assertDeleted($retreat);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('retreat.edit', [$retreat]));

        $response->assertOk();
        $response->assertViewIs('retreats.edit');
        $response->assertViewHas('retreat');
        $response->assertViewHas('d');
        $response->assertViewHas('i');
        $response->assertViewHas('a');
        $response->assertViewHas('c');
        $response->assertViewHas('event_types');
        $response->assertViewHas('is_active');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_payments_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('retreat.payments.edit', ['id' => $retreat->id]));

        $response->assertOk();
        $response->assertViewIs('retreats.payments.edit');
        $response->assertViewHas('retreat');
        $response->assertViewHas('registrations');
        $response->assertViewHas('donation_description');
        $response->assertViewHas('payment_description');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function get_event_by_id_number_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('get_event_by_id_number', ['id_number' => $retreat->id_number]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('retreat.index'));

        $response->assertOk();
        $response->assertViewIs('retreats.index');
        $response->assertViewHas('retreats');
        $response->assertViewHas('oldretreats');
        $response->assertViewHas('defaults');
        $response->assertViewHas('event_types');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_type_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get('retreat/type/{event_type_id}');

        $response->assertOk();
        $response->assertViewIs('retreats.index');
        $response->assertViewHas('retreats');
        $response->assertViewHas('oldretreats');
        $response->assertViewHas('defaults');
        $response->assertViewHas('event_types');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function room_update_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->post(route('retreat.room_update'), [
            // TODO: send request data
        ]);

        $response->assertRedirect(action('RetreatController@index'));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function room_update_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RetreatController::class,
            'room_update',
            \App\Http\Requests\RoomUpdateRetreatRequest::class
        );
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('retreat.show', [$retreat]));

        $response->assertOk();
        $response->assertViewIs('retreats.show');
        $response->assertViewHas('retreat');
        $response->assertViewHas('registrations');
        $response->assertViewHas('status');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_payments_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('retreat.payments', ['id' => $retreat->id]));

        $response->assertOk();
        $response->assertViewIs('retreats.payments.show');
        $response->assertViewHas('retreat');
        $response->assertViewHas('registrations');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_waitlist_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get('retreat/{event_id}/waitlist');

        $response->assertOk();
        $response->assertViewIs('retreats.waitlist');
        $response->assertViewHas('retreat');
        $response->assertViewHas('registrations');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->post(route('retreat.store'), [
            // TODO: send request data
        ]);

        $response->assertRedirect(action('RetreatController@index'));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RetreatController::class,
            'store',
            \App\Http\Requests\StoreRetreatRequest::class
        );
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $retreat = factory(\App\Retreat::class)->create();
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->put(route('retreat.update', [$retreat]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(action('RetreatController@index'));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RetreatController::class,
            'update',
            \App\Http\Requests\UpdateRetreatRequest::class
        );
    }

    // test cases...
}