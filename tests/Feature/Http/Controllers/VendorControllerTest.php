<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\VendorController
 */
class VendorControllerTest extends TestCase
{
    use withFaker;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-contact');

        $response = $this->actingAs($user)->get(route('vendor.create'));

        $response->assertOk();
        $response->assertViewIs('vendors.create');
        $response->assertViewHas('states');
        $response->assertViewHas('countries');
        $response->assertViewHas('defaults');
        $response->assertSeeText('Add a Vendor');
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('delete-contact');
        $vendor = factory(\App\Vendor::class)->create();

        $response = $this->actingAs($user)->delete(route('vendor.destroy', ['vendor' => $vendor]));

        $response->assertRedirect(action('VendorController@index'));
        $this->assertSoftDeleted($vendor);

    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('update-contact');
        $vendor = factory(\App\Vendor::class)->create();

        $response = $this->actingAs($user)->get(route('vendor.edit', $vendor->id));
        $response->assertOk();
        $response->assertViewIs('vendors.edit');
        $response->assertViewHas('vendor');
        $response->assertViewHas('states');
        $response->assertViewHas('countries');
        $response->assertViewHas('defaults');
        $response->assertSeeText(e($vendor->display_name));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-contact');
        $vendor = factory(\App\Vendor::class)->create();

        $response = $this->actingAs($user)->get(route('vendor.index'));

        $vendors = $response->viewData('vendors');

        $response->assertOk();
        $response->assertViewIs('vendors.index');
        $response->assertViewHas('vendors');
        $response->assertSeeText('Vendors');
        $this->assertGreaterThanOrEqual('1',$vendors->count());

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-contact');
        $vendor = factory(\App\Vendor::class)->create();

        $response = $this->actingAs($user)->get(route('vendor.show', ['vendor' => $vendor]));

        $response->assertOk();
        $response->assertViewIs('vendors.show');
        $response->assertViewHas('vendor');
        $response->assertViewHas('relationship_types');
        $response->assertViewHas('files');
        $response->assertSeeText(e($vendor->display_name));

    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('create-contact');
        $vendor_name = $this->faker->company;

        $response = $this->actingAs($user)->post(route('vendor.store'), [
          'organization_name' => $vendor_name,
          'display_name' => $vendor_name,
          'sort_name' => $vendor_name,
          'contact_type' => config('polanco.contact_type.organization'),
          'subcontact_type' => config('polanco.contact_type.vendor'),
        ]);

        $response->assertRedirect(action('VendorController@index'));

        $this->assertDatabaseHas('contact', [
          'contact_type' => config('polanco.contact_type.organization'),
          'subcontact_type' => config('polanco.contact_type.vendor'),
          'sort_name' => $vendor_name,
          'display_name' => $vendor_name,
          'organization_name' => $vendor_name,
        ]);

    }

    /**
     * @test
     */
    public function store_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\VendorController::class,
            'store',
            \App\Http\Requests\StoreVendorRequest::class
        );
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('update-contact');
        $vendor = factory(\App\Vendor::class)->create();
        $original_sort_name = $vendor->sort_name;
        $vendor_name = $this->faker->company;

        $response = $this->actingAs($user)->put(route('vendor.update', $vendor), [
          'sort_name' => $vendor_name,
          'display_name' => $vendor_name,
          'organization_name' => $vendor_name,
          'id' => $vendor->id,
        ]);

        $response->assertRedirect(action('VendorController@show', $vendor->id));

        $updated = \App\Contact::find($vendor->id);

        $response->assertRedirect(action('VendorController@show', $vendor->id));
        $this->assertEquals($updated->sort_name, $vendor_name);
        $this->assertNotEquals($updated->sort_name, $original_sort_name);

    }

    /**
     * @test
     */
    public function update_validates_with_a_form_request()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\VendorController::class,
            'update',
            \App\Http\Requests\UpdateVendorRequest::class
        );
    }

    // test cases...
}
