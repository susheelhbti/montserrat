<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\GroupContact;

/**
 * @see \App\Http\Controllers\TouchpointController
 */
class TouchpointControllerTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function add_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-touchpoint');
        // Create a fake contact to have an interaction with
        // Creat a staff contact and use the authenticated user's email to associate with the staff contact
        // Add the staff contact to the staff group - probably not necessary for this test but for consistency I am adding it here

        $contact = factory(\App\Contact::class)->create();
        $staff = factory(\App\Contact::class)->create();
        $email = factory(\App\Email::class)->create([
            'contact_id' => $staff->id,
            'location_type_id' => config('polanco.location_type.work'),
            'email' => $user->email,
            'is_primary' => '1',
        ]);
        $group_contact = factory(\App\GroupContact::class)->create([
          'group_id' => config('polanco.group_id.staff'),
          'contact_id' => $staff->id,
        ]);

        $response = $this->actingAs($user)->get('touchpoint/add/'.$contact->id);

        $response->assertOk();
        $response->assertViewIs('touchpoints.create');
        $response->assertViewHas('staff');
        $response->assertViewHas('persons');
        $response->assertViewHas('defaults');
        $response->assertSeeText('Create Touchpoint');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function add_group_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-touchpoint');
        // Create a fake contact to have an interaction with
        // Creat a staff contact and use the authenticated user's email to associate with the staff contact
        // Add the staff contact to the staff group - probably not necessary for this test but for consistency I am adding it here

        $staff = factory(\App\Contact::class)->create();
        $email = factory(\App\Email::class)->create([
            'contact_id' => $staff->id,
            'location_type_id' => config('polanco.location_type.work'),
            'email' => $user->email,
            'is_primary' => '1',
        ]);
        $group_contact_staff = factory(\App\GroupContact::class)->create([
          'group_id' => config('polanco.group_id.staff'),
          'contact_id' => $staff->id,
        ]);

        $group = factory(\App\Group::class)->create();

        /* for testing add we don't actually need to create the group members so I'm commenting out

        $number_group_members = $this->faker->numberBetween(2, 10);
        $group_contact = factory(\App\GroupContact::class, $number_group_members)->create([
          'group_id' => $group->id,
        ]);

        */

        $response = $this->actingAs($user)->get('group/' . $group->id . '/touchpoint');

        $response->assertOk();
        $response->assertViewIs('touchpoints.add_group');
        $response->assertViewHas('staff');
        $response->assertViewHas('groups');
        $response->assertViewHas('defaults');
        $response->assertSeeText('Create Group Touchpoint');

    }

    /**
     * @test
     */
    public function add_retreat_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-touchpoint');

        // Create a staff contact and use the authenticated user's email to associate with the staff contact
        // Add the staff contact to the staff group - probably not necessary for this test but for consistency I am adding it here

        $staff = factory(\App\Contact::class)->create();
        $email = factory(\App\Email::class)->create([
            'contact_id' => $staff->id,
            'location_type_id' => config('polanco.location_type.work'),
            'email' => $user->email,
            'is_primary' => '1',
        ]);

        // Create a fake retreat
        // Register a random number of people for that retreat
        // Create touchpoint for the retreatants registered on that retreat
        // Since this is just the add, we don't actually need the retreatants so commenting them out

        $retreat = factory(\App\Retreat::class)->create();
        /* $number_participants = $this->faker->numberBetween(3,15);
        $registration = factory(\App\Registration::class, $number_participants)->create([
            'event_id' => $retreat->id,
        ]);
        */

        $response = $this->actingAs($user)->get('retreat/' . $retreat->id . '/touchpoint');

        $response->assertOk();
        $response->assertViewIs('touchpoints.add_retreat');
        $response->assertViewHas('staff');
        $response->assertViewHas('retreat');
        $response->assertViewHas('retreats');
        $response->assertViewHas('participants');
        $response->assertViewHas('defaults');
        $response->assertSeeText('Create Retreat Touchpoint');
    }

    /**
     * @test
     */
    public function add_retreat_waitlist_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-touchpoint');

        // Create a staff contact and use the authenticated user's email to associate with the staff contact
        // Add the staff contact to the staff group - probably not necessary for this test but for consistency I am adding it here

        $staff = factory(\App\Contact::class)->create();
        $email = factory(\App\Email::class)->create([
            'contact_id' => $staff->id,
            'location_type_id' => config('polanco.location_type.work'),
            'email' => $user->email,
            'is_primary' => '1',
        ]);

        // Create a fake retreat
        // Register a random number of people for that retreat
        // Create touchpoint for the retreatants registered on that retreat
        // Since this is just the add, we don't actually need the retreatants so commenting them out

        $retreat = factory(\App\Retreat::class)->create();

        $response = $this->actingAs($user)->get('retreat/' . $retreat->id . '/waitlist_touchpoint');

        $response->assertOk();
        $response->assertViewIs('touchpoints.add_retreat_waitlist');
        $response->assertViewHas('staff');
        $response->assertViewHas('retreat');
        $response->assertViewHas('retreats');
        $response->assertViewHas('participants');
        $response->assertViewHas('defaults');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-touchpoint');

        $response = $this->actingAs($user)->get(route('touchpoint.create'));

        $response->assertOk();
        $response->assertViewIs('touchpoints.create');
        $response->assertViewHas('staff');
        $response->assertViewHas('persons');
        $response->assertViewHas('defaults');
        $response->assertSeeText('Create Touchpoint');
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('delete-touchpoint');
        $touchpoint = factory(\App\Touchpoint::class)->create();

        $response = $this->actingAs($user)->delete(route('touchpoint.destroy', [$touchpoint]));

        $response->assertRedirect(action('TouchpointController@index'));
        $this->assertSoftDeleted($touchpoint);
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('update-touchpoint');
        $touchpoint = factory(\App\Touchpoint::class)->create();

        $response = $this->actingAs($user)->get(route('touchpoint.edit', [$touchpoint]));

        $response->assertOk();
        $response->assertViewIs('touchpoints.edit');
        $response->assertViewHas('touchpoint');
        $response->assertViewHas('staff');
        $response->assertViewHas('persons');
        $response->assertSeeText('Edit Touchpoint');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-touchpoint');

        $response = $this->actingAs($user)->get(route('touchpoint.index'));

        $response->assertOk();
        $response->assertViewIs('touchpoints.index');
        $response->assertViewHas('touchpoints');
        $response->assertSeeText('Touchpoint Index');
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-touchpoint');
        $touchpoint = factory(\App\Touchpoint::class)->create();

        $response = $this->actingAs($user)->get(route('touchpoint.show', [$touchpoint]));

        $response->assertOk();
        $response->assertViewIs('touchpoints.show');
        $response->assertViewHas('touchpoint');
        $response->assertSeeText('Touchpoint details');
        $response->assertSeeText(e($touchpoint->description));
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-touchpoint');
        $person = factory(\App\Contact::class)->create();
        $staff = factory(\App\Contact::class)->create();
        $email = factory(\App\Email::class)->create([
            'contact_id' => $staff->id,
            'location_type_id' => config('polanco.location_type.work'),
            'email' => $user->email,
            'is_primary' => '1',
        ]);
        $group_contact = factory(\App\GroupContact::class)->create([
          'group_id' => config('polanco.group_id.staff'),
          'contact_id' => $staff->id,
        ]);
        $touched_at = $this->faker->dateTime('now');
        $response = $this->actingAs($user)->post(route('touchpoint.store'), [
          'touched_at' => $touched_at,
          'person_id' => $person->id,
          'staff_id' => $staff->id,
          'type' => array_rand(array_flip(array('Email','Call','Letter','Face','Other'))),
          'notes' => $this->faker->paragraph,
        ]);
        $response->assertRedirect(action('TouchpointController@index'));
        $this->assertDatabaseHas('touchpoints', [
          'touched_at' => $touched_at,
          'person_id' => $person->id,
          'staff_id' => $staff->id,
        ]);

    }

    /**
     * @test
     */
    public function store_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TouchpointController::class,
            'store',
            \App\Http\Requests\StoreTouchpointRequest::class
        );
    }

    /**
     * @test
     */
    public function store_group_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-touchpoint');
        // Create a fake contact to have an interaction with
        // Creat a staff contact and use the authenticated user's email to associate with the staff contact
        // Add the staff contact to the staff group - probably not necessary for this test but for consistency I am adding it here

        $staff = factory(\App\Contact::class)->create();
        $email = factory(\App\Email::class)->create([
            'contact_id' => $staff->id,
            'location_type_id' => config('polanco.location_type.work'),
            'email' => $user->email,
            'is_primary' => '1',
        ]);
        $group_contact_staff = factory(\App\GroupContact::class)->create([
          'group_id' => config('polanco.group_id.staff'),
          'contact_id' => $staff->id,
        ]);

        $group = factory(\App\Group::class)->create();

        $number_group_members = $this->faker->numberBetween(2, 10);
        $group_contact = factory(\App\GroupContact::class, $number_group_members)->create([
          'group_id' => $group->id,
        ]);

        $notes = $this->faker->paragraph;
        $touched_at = $this->faker->dateTime('now');

        $random_group_member = \App\GroupContact::whereGroupId($group->id)->get()->random();

        $response = $this->actingAs($user)->post('touchpoint/add_group', [
            'group_id' => $group->id,
            'touched_at' => $touched_at,
            'staff_id' => $staff->id,
            'type' => array_rand(array_flip(array('Email','Call','Letter','Face','Other'))),
            'notes' => $notes,
        ]);

        $response->assertRedirect(action('GroupController@show', $group->id));
        $this->assertDatabaseHas('touchpoints', [
          'touched_at' => $touched_at,
          'person_id' => $random_group_member->contact_id,
          'staff_id' => $staff->id,
          'notes' => $notes,
        ]);
    }

    /**
     * @test
     */
    public function store_group_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TouchpointController::class,
            'store_group',
            \App\Http\Requests\StoreGroupTouchpointRequest::class
        );
    }

    /**
     * @test
     */
    public function store_retreat_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-touchpoint');

        // Create a staff contact and use the authenticated user's email to associate with the staff contact
        // Add the staff contact to the staff group - probably not necessary for this test but for consistency I am adding it here

        $staff = factory(\App\Contact::class)->create();
        $email = factory(\App\Email::class)->create([
            'contact_id' => $staff->id,
            'location_type_id' => config('polanco.location_type.work'),
            'email' => $user->email,
            'is_primary' => '1',
        ]);

        // Create a fake retreat
        // Register a random number of people for that retreat
        // Create touchpoint for the retreatants registered on that retreat

        $retreat = factory(\App\Retreat::class)->create();
        $number_participants = $this->faker->numberBetween(3,15);

        // criteria set from search criteria in touchpoint controller's store_retreat method
        $participants = factory(\App\Registration::class, $number_participants)->create([
            'event_id' => $retreat->id,
            'status_id' => config('polanco.registration_status_id.registered'),
            'role_id' => config('polanco.participant_role_id.retreatant'),
            'canceled_at' => null,
        ]);

        $notes = $this->faker->paragraph;
        $touched_at = $this->faker->dateTime('now');

        // where criteria copied from touchpoint controller store_retreat method for consistency
        $actual_participants = \App\Registration::whereStatusId(config('polanco.registration_status_id.registered'))->whereEventId($retreat->id)->whereRoleId(config('polanco.participant_role_id.retreatant'))->whereNull('canceled_at')->get();
        $random_participant = $actual_participants->random();

        $response = $this->actingAs($user)->post('touchpoint/add_retreat', [
            'event_id' => $retreat->id,
            'touched_at' => $touched_at,
            'staff_id' => $staff->id,
            'type' => array_rand(array_flip(array('Email','Call','Letter','Face','Other'))),
            'notes' => $notes,
        ]);

        $response->assertRedirect(action('RetreatController@show', $retreat->id));

        $this->assertDatabaseHas('touchpoints', [
          'touched_at' => $touched_at,
          'person_id' => $random_participant->contact_id,
          'staff_id' => $staff->id,
          'notes' => $notes,
        ]);

    }

    /**
     * @test
     */
    public function store_retreat_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TouchpointController::class,
            'store_retreat',
            \App\Http\Requests\StoreRetreatTouchpointRequest::class
        );
    }

    /**
     * @test
     */
    public function store_retreat_waitlist_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-touchpoint');

        // Create a staff contact and use the authenticated user's email to associate with the staff contact
        // Add the staff contact to the staff group - probably not necessary for this test but for consistency I am adding it here

        $staff = factory(\App\Contact::class)->create();
        $email = factory(\App\Email::class)->create([
            'contact_id' => $staff->id,
            'location_type_id' => config('polanco.location_type.work'),
            'email' => $user->email,
            'is_primary' => '1',
        ]);

        // Create a fake retreat
        // Register a random number of people for that retreat
        // Create touchpoint for the retreatants registered on that retreat

        $retreat = factory(\App\Retreat::class)->create();
        $number_participants = $this->faker->numberBetween(3,15);

        // criteria set from search criteria in touchpoint controller's store_retreat method
        $participants = factory(\App\Registration::class, $number_participants)->create([
            'event_id' => $retreat->id,
            'status_id' => config('polanco.registration_status_id.waitlist'),
            'role_id' => config('polanco.participant_role_id.retreatant'),
            'canceled_at' => null,
        ]);

        $notes = $this->faker->paragraph;
        $touched_at = $this->faker->dateTime('now');

        // where criteria copied from touchpoint controller store_retreat method for consistency
        $actual_participants = \App\Registration::whereStatusId(config('polanco.registration_status_id.waitlist'))->whereEventId($retreat->id)->whereRoleId(config('polanco.participant_role_id.retreatant'))->whereNull('canceled_at')->get();
        $random_participant = $actual_participants->random();

        $response = $this->actingAs($user)->post('touchpoint/add_retreat_waitlist', [
            'event_id' => $retreat->id,
            'touched_at' => $touched_at,
            'staff_id' => $staff->id,
            'type' => array_rand(array_flip(array('Email','Call','Letter','Face','Other'))),
            'notes' => $notes,
        ]);

        $response->assertRedirect(action('RetreatController@show', $retreat->id));

        $this->assertDatabaseHas('touchpoints', [
          'touched_at' => $touched_at,
          'person_id' => $random_participant->contact_id,
          'staff_id' => $staff->id,
          'notes' => $notes,
      ]);
    }

    /**
     * @test
     */
    public function store_retreat_waitlist_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TouchpointController::class,
            'store_retreat_waitlist',
            \App\Http\Requests\StoreRetreatWaitlistTouchpointRequest::class
        );
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('update-touchpoint');
        $person = factory(\App\Contact::class)->create();
        $staff = factory(\App\Contact::class)->create();
        $email = factory(\App\Email::class)->create([
            'contact_id' => $staff->id,
            'location_type_id' => config('polanco.location_type.work'),
            'email' => $user->email,
            'is_primary' => '1',
        ]);
        $group_contact = factory(\App\GroupContact::class)->create([
          'group_id' => config('polanco.group_id.staff'),
          'contact_id' => $staff->id,
        ]);

        $touchpoint = factory(\App\Touchpoint::class)->create();
        $original_staff_id = $touchpoint->staff_id;
        $original_person_id = $touchpoint->person_id;
        $response = $this->actingAs($user)->put(route('touchpoint.update', [$touchpoint]), [
          'id' => $touchpoint->id,
          'touched_at' => $this->faker->dateTime('now'),
          'person_id' => $person->id,
          'staff_id' => $staff->id,
          'notes' => $this->faker->paragraph,
        ]);

        $touchpoint->refresh();

        $response->assertRedirect(action('TouchpointController@index'));
        $this->AssertEquals($person->id, $touchpoint->person_id);
        $this->AssertEquals($staff->id, $touchpoint->staff_id);
        $this->AssertNotEquals($original_staff_id, $touchpoint->staff_id);

    }

    /**
     * @test
     */
    public function update_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TouchpointController::class,
            'update',
            \App\Http\Requests\UpdateTouchpointRequest::class
        );
    }

    // test cases...
}
