<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RoomController
 */
class RoomControllerTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-room');

        $response = $this->actingAs($user)->get(route('room.create'));

        $response->assertOk();
        $response->assertViewIs('rooms.create');
        $response->assertViewHas('locations');
        $response->assertSeeText('Add A Room');

    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('delete-room');
        $room = factory(\App\Room::class)->create();

        $response = $this->actingAs($user)->delete(route('room.destroy', [$room]));

        $response->assertRedirect(action('RoomController@index'));
        $this->assertSoftDeleted($room);

    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('update-room');
        $room = factory(\App\Room::class)->create();

        $response = $this->actingAs($user)->get(route('room.edit', [$room]));

        $response->assertOk();
        $response->assertViewIs('rooms.edit');
        $response->assertViewHas('room');
        $response->assertViewHas('locations');
        $room_data = $response->viewData('room');
        $this->assertEquals($room_data->description, $room->description);
        $response->assertSeeText('Edit Room');

    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-room');

        $response = $this->actingAs($user)->get(route('room.index'));

        $response->assertOk();
        $response->assertViewIs('rooms.index');
        $response->assertViewHas('roomsort');
        $response->assertSeeText('Room Index');
    }

    /**
     * @test
     */
    public function schedule_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-room');

        $response = $this->actingAs($user)->get(route('rooms'));

        $response->assertOk();
        $response->assertViewIs('rooms.sched2');
        $response->assertViewHas('roomsort');
        $response->assertViewHas('dts');
        $response->assertViewHas('m');
        $response->assertViewHas('previous_link');
        $response->assertViewHas('next_link');
        $response->assertSeeText('Room Schedules');
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-room');
        $room = factory(\App\Room::class)->create();

        $response = $this->actingAs($user)->get(route('room.show', [$room]));

        $response->assertOk();
        $response->assertViewIs('rooms.show');
        $response->assertViewHas('room');
        $response->assertSeeText(e($room->description));
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-room');

        $location = factory(\App\Location::class)->create();
        $name = 'New ' . $this->faker->lastName . ' Suite';
        $description = $this->faker->catchPhrase;

        $response = $this->actingAs($user)->post(route('room.store'), [
          'building_id' => $location->id,
          'name' => $name,
          'description' => $description,
          'notes' => $this->faker->sentence,
          'access' => $this->faker->word,
          'type' => $this->faker->word,
          'occupancy' => $this->faker->randomDigitNotNull,
          'status' => $this->faker->word,
        ]);

        $response->assertRedirect(action('RoomController@index'));
        $this->assertDatabaseHas('rooms', [
          'name' => $name,
          'description' => $description,
          'building_id' => $location->id,
        ]);


    }

    /**
     * @test
     */
    public function store_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RoomController::class,
            'store',
            \App\Http\Requests\StoreRoomRequest::class
        );
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('update-room');
        $room = factory(\App\Room::class)->create();

        $original_description = $room->description;
        $new_location = factory(\App\Location::class)->create();
        $new_name = 'Renovated ' . $this->faker->lastName . ' Suite';
        $new_description = $this->faker->catchPhrase;

        $response = $this->actingAs($user)->put(route('room.update', [$room]), [
          'id' => $room->id,
          'building_id' => $new_location->id,
          'name' => $new_name,
          'description' => $new_description,
        ]);

        $room->refresh();
        $response->assertRedirect(action('RoomController@index'));
        $this->assertEquals($new_description,$room->description);
        $this->assertNotEquals($original_description,$room->description);
    }

    /**
     * @test
     */
    public function update_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RoomController::class,
            'update',
            \App\Http\Requests\UpdateRoomRequest::class
        );
    }

    // test cases...
}
