<?php

namespace Tests\Feature\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PageController
 */
class PageControllerTest extends TestCase
{
    /**
     * @test
     */
    public function about_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('about'));

        $response->assertOk();
        $response->assertViewIs('pages.about');
    }

    /**
     * @test
     */
    public function bookstore_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('bookstore'));

        $response->assertOk();
        $response->assertViewIs('pages.bookstore');
    }

    /**
     * @test
     */
    public function config_google_client_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-admin-menu');

        $response = $this->actingAs($user)->get(route('admin.config.google_client'));

        $response->assertOk();
        $response->assertViewIs('admin.config.google_client');
    }

    /**
     * @test
     */
    public function config_google_client_returns_403()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('admin.config.google_client'));
        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function config_mailgun_displays_view()
    {
        $user = $this->createUserWithPermission('show-admin-menu');

        $response = $this->actingAs($user)->get(route('admin.config.mailgun'));

        $response->assertOk();
        $response->assertViewIs('admin.config.mailgun');
    }

    /**
     * @test
     */
    public function config_mailgun_returns_403()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('admin.config.mailgun'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function config_twilio_displays_view()
    {
        $user = $this->createUserWithPermission('show-admin-menu');

        $response = $this->actingAs($user)->get(route('admin.config.twilio'));

        $response->assertOk();
        $response->assertViewIs('admin.config.twilio');
    }

    /**
     * @test
     */
    public function config_twilio_returns_403()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('admin.config.twilio'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function contact_info_report_displays_view()
    {
        $user = $this->createUserWithPermission('show-contact');
        $contact = factory(\App\Contact::class)->create();

        $response = $this->actingAs($user)->get('report/contact_info_report/' . $contact->id);

        $response->assertOk();
        $response->assertViewIs('reports.contact_info');
        $response->assertViewHas('person', $contact);
    }

    /**
     * @test
     */
    public function contact_info_returns_404()
    {
        $user = $this->createUserWithPermission('show-contact');

        $response = $this->actingAs($user)->get('report/contact_info_report/-1');

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function finance_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('finance'));

        $response->assertOk();
        $response->assertViewIs('pages.finance');
    }

    /**
     * @test
     */
    public function finance_agcacknowledge_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-donation');
        $payment = factory(\App\Payment::class)->create();

        $response = $this->actingAs($user)->get('donation/' . $payment->donation_id . '/agcacknowledge');

        $response->assertOk();
        $response->assertViewIs('reports.finance.agcacknowledge');
        $response->assertViewHas('donation');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function finance_agcacknowledge_returns_403()
    {
        $user = factory(\App\User::class)->create();
        $payment = factory(\App\Payment::class)->create();

        $response = $this->actingAs($user)->get('donation/' . $payment->donation_id . '/agcacknowledge');

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function finance_cash_deposit_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-donation');

        $response = $this->actingAs($user)->get(route('report.finance.cash_deposit'));

        $response->assertOk();
        $response->assertViewIs('reports.finance.cash_deposit');
        $response->assertViewHas('report_date');
        $response->assertViewHas('grouped_payments');
        $response->assertViewHas('grand_total');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function finance_cc_deposit_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-donation');

        $response = $this->actingAs($user)->get(route('report.finance.cc_deposit'));

        $response->assertOk();
        $response->assertViewIs('reports.finance.cc_deposit');
        $response->assertViewHas('report_date');
        $response->assertViewHas('grouped_payments');
        $response->assertViewHas('grand_total');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function finance_deposits_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-donation');

        $response = $this->actingAs($user)->get('report/finance/deposits');

        $response->assertOk();
        $response->assertViewIs('reports.finance.deposits');
        $response->assertViewHas('grouped_payments');
        $response->assertViewHas('payments');

    }

    /**
     * @test
     */
    public function finance_invoice_report_displays_view()
    {
        $this->withoutExceptionHandling();
        $user = $this->createUserWithPermission('show-donation');
        $donation = factory(\App\Donation::class)->create();

        $response = $this->actingAs($user)->get('donation/' . $donation->donation_id . '/invoice');

        $response->assertOk();
        $response->assertViewIs('reports.finance.invoice');
        $response->assertViewHas('donation');
        $response->assertSee('Invoice #'.$donation->donation_id);

    }

    /**
     * @test
     */
    public function finance_invoice_returns_404()
    {
        $user = $this->createUserWithPermission('show-donation');

        $response = $this->actingAs($user)->get('donation/-1/invoice');

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function finance_reconcile_deposit_show_returns_an_ok_response()
    {
        $user = factory(\App\User::class)->create();
        $user->assignRole('test-role:finance_reconcile_deposit_show');

        $response = $this->actingAs($user)->get(route('depositreconcile.show'));

        $response->assertOk();
        $response->assertViewIs('reports.finance.reconcile_deposits');
        $response->assertViewHas('diffpg');
        $response->assertViewHas('diffrg');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function finance_retreatdonations_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-donation');
        $retreat = factory(\App\Retreat::class)->create();
        $donation = factory(\App\Donation::class)->create([
          'event_id' => $retreat->id
        ]);


        $response = $this->actingAs($user)->get('report/finance/retreatdonations/'.$retreat->idnumber);

        $response->assertOk();
        $response->assertViewIs('reports.finance.retreatdonations');
        $response->assertViewHas('retreat');
        $response->assertViewHas('grouped_donations');
        $response->assertViewHas('donations');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function grounds_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('grounds'));

        $response->assertOk();
        $response->assertViewIs('pages.grounds');
    }

    /**
     * @test
     */
    public function housekeeping_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('housekeeping'));

        $response->assertOk();
        $response->assertViewIs('pages.housekeeping');
    }

    /**
     * @test
     */
    public function kitchen_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('kitchen'));

        $response->assertOk();
        $response->assertViewIs('pages.kitchen');
    }

    /**
     * @test
     */
    public function maintenance_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('maintenance'));

        $response->assertOk();
        $response->assertViewIs('pages.maintenance');
    }

    /**
     * @test
     */
    public function reservation_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('reservation'));

        $response->assertOk();
        $response->assertViewIs('pages.reservation');
    }

    /**
     * @test
     */
    public function restricted_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('restricted'));

        $response->assertOk();
        $response->assertViewIs('pages.restricted');
    }

    /**
     * @test
     */
    public function retreat_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('retreats'));

        $response->assertOk();
        $response->assertViewIs('pages.retreat');
    }

    /**
     * @test
     */
    public function retreatantinforeport_displays_view()
    {
        $user = factory(\App\User::class)->create();
        $user->assignRole('test-role:retreatantinforeport');
        $retreat = factory(\App\Retreat::class)->create();
        $registrants = factory(\App\Registration::class, 2)->create([
            'event_id' => $retreat->id,
            'canceled_at' => NULL
        ]);

        $response = $this->actingAs($user)->get('report/retreatantinfo/'.$retreat->idnumber);
        $response->assertOk();
        $response->assertViewIs('reports.retreatantinfo2');
        $response->assertViewHas('registrations');
        $registrations = $response->viewData('registrations');
        $this->assertCount(2, $registrations);
        $this->assertEquals($registrants->sortBy('id')->pluck('id'), $registrations->sortBy('id')->pluck('id'));
    }

    /**
     * @test
     */
    public function retreatlistingreport_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-contact');
        $retreat = factory(\App\Retreat::class)->create();
        $registrants = factory(\App\Registration::class, 2)->create([
            'event_id' => $retreat->id,
            'canceled_at' => NULL
        ]);

        $response = $this->actingAs($user)->get('report/retreatlisting/'.$retreat->idnumber);

        $response->assertOk();
        $response->assertViewIs('reports.retreatlisting');
        $response->assertViewHas('registrations');
        $response->assertSee('Retreat Listing');
        $response->assertSee('Registered Retreatant');
    }

    /**
     * @test
     */
    public function retreatrosterreport_returns_an_ok_response()
    {
        $user = $this->createUserWithPermission('show-contact');
        $retreat = factory(\App\Retreat::class)->create();

        $response = $this->actingAs($user)->get('report/retreatroster/'.$retreat->idnumber);

        $response->assertOk();
        $response->assertViewIs('reports.retreatroster');
        $response->assertViewHas('registrations');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function support_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('support'));

        $response->assertOk();
        $response->assertViewIs('pages.support');
        $response->assertSee('Support Page');
    }

    /**
     * @test
     */
    public function user_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get(route('users'));

        $response->assertOk();
        $response->assertViewIs('pages.user');
    }

    /**
     * @test
     */
    public function welcome_displays_view()
    {
        $user = factory(\App\User::class)->create();

        $mock = new MockHandler([
            new Response(200, [], '<p><b>Hello</b>, World!</p>'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $this->swap(Client::class, $client);

        $response = $this->actingAs($user)->get(route('welcome'));

        $response->assertOk();
        $response->assertViewIs('welcome');
        $response->assertViewHas('quote', '<b>Hello</b>, World!');
    }
}
