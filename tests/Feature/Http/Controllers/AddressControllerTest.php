<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\StateProvince;

/**
 * @see \App\Http\Controllers\AddressController
 */
class AddressControllerTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function create_returns_an_ok_response() //create method empty - nothing to test
    {
        $user = $this->createUserWithPermission('create-address');

        $response = $this->actingAs($user)->get(route('address.create'));

        $response->assertOk();
        $response->assertViewIs('addresses.create');
        $response->assertViewHas('contacts');
        $response->assertViewHas('states');
        $response->assertViewHas('countries');
        $response->assertSeeText('Create address');
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $address = factory(\App\Address::class)->create();
        $contact_id = $address->contact_id;
        $user = $this->createUserWithPermission('delete-address');

        $response = $this->actingAs($user)->delete(route('address.destroy', [$address]));

        $response->assertRedirect(action('PersonController@show', $contact_id));
        $this->assertSoftDeleted($address);
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $address = factory(\App\Address::class)->create();
        $user = $this->createUserWithPermission('update-address');

        $response = $this->actingAs($user)->get(route('address.edit', [$address]));

        $response->assertOk();
        $response->assertViewIs('addresses.edit');
        $response->assertViewHas('address');
        $response->assertViewHas('countries');
        $response->assertViewHas('states');
        $response->assertViewHas('contacts');
        $response->assertViewHas('location_types');
        $response->assertSeeText('Edit address');
        // TODO: would be nice to assertSeeText($address->street_address) is the default/populated value of the field but I couldn't figure out why this text is not in the response
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-address');

        $response = $this->actingAs($user)->get(route('address.index'));

        $response->assertOk();
        $response->assertViewIs('addresses.index');
        $response->assertViewHas('addresses');
        $response->assertSeeText('Addresses');
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {

        $address = factory(\App\Address::class)->create();
        $user = $this->createUserWithPermission('show-address');

        $response = $this->actingAs($user)->get(route('address.show', [$address]));

        $response->assertOk();
        $response->assertViewIs('addresses.show');

        $response->assertSeeText('Address details');
        $response->assertSeeText($address->street_address);

    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {   $this->withoutExceptionHandling();
        $user = $this->createUserWithPermission('create-address');
        $contact = factory(\App\Contact::class)->create();
        $random_location_type = \App\LocationType::get()->random();
        $random_state = \App\StateProvince::whereCountryId(config('polanco.country_id_usa'))->get()->random();
        $random_street_address = $this->faker->streetAddress;
        
        $response = $this->actingAs($user)->post(route('address.store'), [
            'contact_id' => $contact->id,
            'location_type_id' => $random_location_type->id,
            'is_primary' => $this->faker->boolean,
            'street_address' => $random_street_address,
            'supplemental_address_1' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state_province_id' => $random_state->id,
            'postal_code' => $this->faker->postcode,
            'country_id' => config('polanco.country_id_usa'),
        ]);

        $response->assertRedirect(action('AddressController@index'));
        $this->assertDatabaseHas('address', [
          'contact_id' => $contact->id,
          'street_address' => $random_street_address,
        ]);
    }

    /**
     * @test
     */
    public function store_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AddressController::class,
            'store',
            \App\Http\Requests\StoreAddressRequest::class
        );
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('update-address');
        $address = factory(\App\Address::class)->create();
        $contact_id = $address->contact_id;
        $original_street_address = $address->street_address;

        $response = $this->actingAs($user)->put(route('address.update', [$address]), [
            'contact_id' => $address->contact_id,
            'location_type_id' => $address->location_type_id,
            'street_address' => $this->faker->streetAddress,
        ]);

        // $response->assertRedirect(action('AddressController@show', $address->id));

        $updated_address = \App\Address::find($address->id);
        $this->assertEquals($updated_address->contact_id,$contact_id);
        $this->assertNotEquals($updated_address->street_address, $original_street_address);
    }


        /**
         * @test
         */
        public function update_validates_with_a_form_request()
        {
            $this->assertActionUsesFormRequest(
                \App\Http\Controllers\AddressController::class,
                'update',
                \App\Http\Requests\UpdateAddressRequest::class
            );
        }
}
