<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRegistrationRequest;
use App\Http\Requests\StoreRegistrationRequest;
use App\Http\Requests\UpdateGroupRegistrationRequest;
use App\Http\Requests\UpdateRegistrationRequest;
use App\Mail\RegistrationEventChange;
use App\Mail\RetreatRegistration;
use App\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('confirmAttendance');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('show-registration');

        $registrations = \App\Registration::whereHas('retreat', function ($query) {
            $query->where('end_date', '>=', date('Y-m-d'));
        })->orderBy('register_date', 'desc')->with('retreatant', 'retreat', 'room')->get();
        //dd($registrations);
        return view('registrations.index', compact('registrations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create-registration');

        $retreats = \App\Retreat::select(DB::raw('CONCAT(idnumber, "-", title, " (",DATE_FORMAT(start_date,"%m-%d-%Y"),")") as description'), 'id')->where('end_date', '>', \Carbon\Carbon::today()->subWeek())->where('is_active', '=', 1)->orderBy('start_date')->pluck('description', 'id');
        $retreats->prepend('Unassigned', 0);
        $retreatants = \App\Contact::whereContactType(config('polanco.contact_type.individual'))->orderBy('sort_name')->pluck('sort_name', 'id');

        $rooms = \App\Room::orderby('name')->pluck('name', 'id');
        $rooms->prepend('Unassigned', 0);

        $dt_today = \Carbon\Carbon::today();
        $defaults['today'] = $dt_today->month.'/'.$dt_today->day.'/'.$dt_today->year;
        $defaults['retreat_id'] = 0;
        $defaults['is_multi_registration'] = false;
        $defaults['registration_source'] = config('polanco.registration_source');
        $defaults['participant_status_type'] = \App\ParticipantStatus::whereIsActive(1)->pluck('name', 'id');

        return view('registrations.create', compact('retreats', 'retreatants', 'rooms', 'defaults'));
    }

    public function add($id)
    {
        $this->authorize('create-registration');
        $retreats = \App\Retreat::select(DB::raw('CONCAT(idnumber, "-", title, " (",DATE_FORMAT(start_date,"%m-%d-%Y"),")") as description'), 'id')->where('end_date', '>', \Carbon\Carbon::today()->subWeek())->where('is_active', '=', 1)->orderBy('start_date')->pluck('description', 'id');
        $retreats->prepend('Unassigned', 0);
        $retreatant = \App\Contact::findOrFail($id);
        if ($retreatant->contact_type == config('polanco.contact_type.individual')) {
            $retreatants = \App\Contact::whereContactType(config('polanco.contact_type.individual'))->orderBy('sort_name')->pluck('sort_name', 'id');
        }
        if ($retreatant->contact_type == config('polanco.contact_type.organization')) {
            $retreatants = \App\Contact::whereContactType(config('polanco.contact_type.organization'))->whereSubcontactType($retreatant->subcontact_type)->orderBy('sort_name')->pluck('sort_name', 'id');
        }

        $rooms = \App\Room::orderby('name')->pluck('name', 'id');
        $rooms->prepend('Unassigned', 0);

        $defaults['contact_id'] = $id;
        $defaults['retreat_id'] = 0;
        $dt_today = \Carbon\Carbon::today();
        $defaults['today'] = $dt_today->month.'/'.$dt_today->day.'/'.$dt_today->year;
        $defaults['is_multi_registration'] = false;
        $defaults['registration_source'] = config('polanco.registration_source');
        $defaults['participant_status_type'] = \App\ParticipantStatus::whereIsActive(1)->pluck('name', 'id');

        return view('registrations.create', compact('retreats', 'retreatants', 'rooms', 'defaults'));
    }

    public function add_group($id)
    {
        $this->authorize('create-registration');

        $retreats = \App\Retreat::select(DB::raw('CONCAT(idnumber, "-", title, " (",DATE_FORMAT(start_date,"%m-%d-%Y"),")") as description'), 'id')->where('end_date', '>', \Carbon\Carbon::today()->subWeek())->orderBy('start_date')->pluck('description', 'id');
        $retreats->prepend('Unassigned', 0);
        $groups = \App\Group::orderBy('title')->pluck('title', 'id');

        $rooms = \App\Room::orderby('name')->pluck('name', 'id');
        $rooms->prepend('Unassigned', 0);

        $defaults['group_id'] = $id;
        $defaults['retreat_id'] = 0;
        $dt_today = \Carbon\Carbon::today();
        $defaults['today'] = $dt_today->month.'/'.$dt_today->day.'/'.$dt_today->year;
        $defaults['registration_source'] = config('polanco.registration_source');
        $defaults['participant_status_type'] = \App\ParticipantStatus::whereIsActive(1)->pluck('name', 'id');

        return view('registrations.add_group', compact('retreats', 'groups', 'rooms', 'defaults'));
        //dd($retreatants);
    }

    public function register($retreat_id = 0, $contact_id = 0)
    {
        $this->authorize('create-registration');

        if ($retreat_id > 0) {
            $retreats = \App\Retreat::select(DB::raw('CONCAT(idnumber, "-", title, " (",DATE_FORMAT(start_date,"%m-%d-%Y"),")") as description'), 'id')->whereId($retreat_id)->orderBy('start_date')->pluck('description', 'id');
        } else {
            $retreats = \App\Retreat::select(DB::raw('CONCAT(idnumber, "-", title, " (",DATE_FORMAT(start_date,"%m-%d-%Y"),")") as description'), 'id')->where('end_date', '>', \Carbon\Carbon::today())->orderBy('start_date')->pluck('description', 'id');
        }
        $retreats->prepend('Unassigned', 0);
        /* get the current retreat to determine the type of retreat
         * based on the type of retreat, determine if we should allow multiple registrations
         * multiple registrations should not have a room assignment (use assign rooms if needed)
         */
        $retreat = \App\Retreat::findOrFail($retreat_id);

        // Day , Conference, Contract, Diocesan, Meeting, Workshop
        $multi_registration_event_types = [config('polanco.event_type.day'), config('polanco.event_type.contract'), config('polanco.event_type.conference'), config('polanco.event_type.diocesan'), config('polanco.event_type.meeting'), config('polanco.event_type.workshop'), config('polanco.event_type.jesuit')];
        if (in_array($retreat->event_type_id, $multi_registration_event_types)) {
            $defaults['is_multi_registration'] = true;
        } else {
            $defaults['is_multi_registration'] = false;
        }
        if ($contact_id > 0) {
            $retreatants = \App\Contact::whereId($contact_id)->orderBy('sort_name')->pluck('sort_name', 'id');
        } else {
            $retreatants = \App\Contact::orderBy('sort_name')->pluck('sort_name', 'id');
        }
        $retreatants->prepend('Unassigned', 0);

        $rooms = \App\Room::orderby('name')->pluck('name', 'id');
        $rooms->prepend('Unassigned', 0);

        $dt_today = \Carbon\Carbon::today();
        $defaults['retreat_id'] = $retreat_id;
        $defaults['contact_id'] = $contact_id;
        $defaults['today'] = $dt_today->month.'/'.$dt_today->day.'/'.$dt_today->year;
        $defaults['registration_source'] = config('polanco.registration_source');
        $defaults['participant_status_type'] = \App\ParticipantStatus::whereIsActive(1)->pluck('name', 'id');

        return view('registrations.create', compact('retreats', 'retreatants', 'rooms', 'defaults'));
        //dd($retreatants);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRegistrationRequest $request)
    {
        $this->authorize('create-registration');

        $rooms = $request->input('rooms');
        $num_registrants = $request->input('num_registrants');
        //TODO: Should we check and verify that the contact type is an organization to allow multiselect or just allow any registration to book multiple rooms?
        $retreat = \App\Retreat::findOrFail($request->input('event_id'));
        $contact = \App\Contact::findOrFail($request->input('contact_id'));
        /*
         * Used primarily for registering groups
         * If a number of registrants is selected, then add that many registrations
         * num_registrants causes rooms to be ignored (either use num_registrants and assign rooms later
         * or reserve rooms - for double occupancy rooms you have to do this twice to get the number or retreatants correct
         *
         */
        if ($num_registrants > 0) {
            for ($i = 1; $i <= $num_registrants; $i++) {
                $registration = new \App\Registration;
                $registration->event_id = $request->input('event_id');
                $registration->contact_id = $request->input('contact_id');
                $registration->source = $request->input('source');
                $registration->status_id = $request->input('status_id');
                $registration->register_date = $request->input('register_date');
                $registration->attendance_confirm_date = $request->input('attendance_confirm_date');
                if (! empty($request->input('canceled_at'))) {
                    $registration->canceled_at = $request->input('canceled_at');
                }
                if (! empty($request->input('arrived_at'))) {
                    $registration->arrived_at = $request->input('arrived_at');
                }
                if (! empty($request->input('departed_at'))) {
                    $registration->departed_at = $request->input('departed_at');
                }
                $registration->room_id = null;
                $registration->registration_confirm_date = $request->input('registration_confirm_date');
                $registration->confirmed_by = $request->input('confirmed_by');
                $registration->deposit = $request->input('deposit');
                $registration->notes = $request->input('notes');
                $registration->save();
            }
        } else {
            foreach ($rooms as $room) {
                //ensure that it is a valid room (not N/A)
                $registration = new \App\Registration;
                $registration->event_id = $request->input('event_id');
                $registration->contact_id = $request->input('contact_id');
                $registration->source = $request->input('source');
                $registration->status_id = $request->input('status_id');
                $registration->register_date = $request->input('register_date');
                $registration->attendance_confirm_date = $request->input('attendance_confirm_date');
                if (! empty($request->input('canceled_at'))) {
                    $registration->canceled_at = $request->input('canceled_at');
                }
                if (! empty($request->input('arrived_at'))) {
                    $registration->arrived_at = $request->input('arrived_at');
                }
                if (! empty($request->input('departed_at'))) {
                    $registration->departed_at = $request->input('departed_at');
                }
                $registration->room_id = $room;
                $registration->registration_confirm_date = $request->input('registration_confirm_date');
                $registration->confirmed_by = $request->input('confirmed_by');
                $registration->deposit = $request->input('deposit');
                $registration->notes = $request->input('notes');
                $registration->remember_token = Str::random(60);
                $registration->save();
                //TODO: verify that the newly created room assignment does not conflict with an existing one
            }
        }

        return Redirect::action('RegistrationController@index');
    }

    public function store_group(StoreGroupRegistrationRequest $request)
    {
        $this->authorize('create-registration');

        $retreat = \App\Retreat::findOrFail($request->input('event_id'));
        $group_members = \App\GroupContact::whereGroupId($request->input('group_id'))->whereStatus('Added')->get();
        foreach ($group_members as $group_member) {
            //ensure that it is a valid room (not N/A)
            $registration = new \App\Registration;
            $registration->event_id = $request->input('event_id');
            $registration->contact_id = $group_member->contact_id;
            $registration->status_id = $request->input('status_id');
            $registration->register_date = $request->input('register_date');
            $registration->attendance_confirm_date = $request->input('attendance_confirm_date');
            if (! empty($request->input('canceled_at'))) {
                $registration->canceled_at = $request->input('canceled_at');
            }
            if (! empty($request->input('arrived_at'))) {
                $registration->arrived_at = $request->input('arrived_at');
            }
            if (! empty($request->input('departed_at'))) {
                $registration->departed_at = $request->input('departed_at');
            }
            $registration->room_id = 0;
            $registration->registration_confirm_date = $request->input('registration_confirm_date');
            $registration->confirmed_by = $request->input('confirmed_by');
            $registration->deposit = $request->input('deposit');
            $registration->notes = $request->input('notes');
            $registration->save();
            //TODO: verify that the newly created room assignment does not conflict with an existing one
        }

        return Redirect::action('RetreatController@show', $retreat->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('show-registration');
        $registration = \App\Registration::with('retreat', 'retreatant', 'room')->findOrFail($id);

        return view('registrations.show', compact('registration')); //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update-registration');

        $registration = \App\Registration::with('retreatant', 'retreat', 'room')->findOrFail($id);
        $retreatant = \App\Contact::findOrFail($registration->contact_id);
        $retreats = \App\Retreat::select(DB::raw('CONCAT(idnumber, "-", title, " (",DATE_FORMAT(start_date,"%m-%d-%Y"),")") as description'), 'id')->where('end_date', '>', \Carbon\Carbon::today())->orderBy('start_date')->pluck('description', 'id');

        //TODO: we will want to be able to switch between types when going from a group registration to individual room assignment
        if ($retreatant->contact_type == config('polanco.contact_type.individual')) {
            $retreatants = \App\Contact::whereContactType(config('polanco.contact_type.individual'))->orderBy('sort_name')->pluck('sort_name', 'id');
        }
        if ($retreatant->contact_type == config('polanco.contact_type.organization')) {
            $retreatants = \App\Contact::whereContactType(config('polanco.contact_type.organization'))->whereSubcontactType($retreatant->subcontact_type)->orderBy('sort_name')->pluck('sort_name', 'id');
        }

        $rooms = \App\Room::orderby('name')->pluck('name', 'id');
        $rooms->prepend('Unassigned', 0);

        /* Check to see if the current registration is for a past retreat and if so, add it to the collection */
        // $retreats[0] = 'Unassigned';

        if ($registration->retreat->end < \Carbon\Carbon::now()) {
            $retreats[$registration->event_id] = $registration->retreat->idnumber.'-'.$registration->retreat->title.' ('.date('m-d-Y', strtotime($registration->retreat->start_date)).')';
        }

        $defaults['registration_source'] = config('polanco.registration_source');
        $defaults['participant_status_type'] = \App\ParticipantStatus::whereIsActive(1)->pluck('name', 'id');

        return view('registrations.edit', compact('registration', 'retreats', 'rooms', 'defaults'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRegistrationRequest $request, $id)
    {
        $this->authorize('update-registration');

        $registration = \App\Registration::findOrFail($request->input('id'));
        $retreat = \App\Retreat::findOrFail($request->input('event_id'));

        $registration->event_id = $request->input('event_id');
        // TODO: pull this from the retreat's start_date and end_date
        //$registration->start = $retreat->start;
        //$registration->end = $retreat->end;
        //$registration->contact_id= $request->input('contact_id');
        $registration->status_id = $request->input('status_id');
        $registration->register_date = $request->input('register_date');
        $registration->attendance_confirm_date = $request->input('attendance_confirm_date');
        $registration->registration_confirm_date = $request->input('registration_confirm_date');
        $registration->source = $request->input('source');
        $registration->confirmed_by = $request->input('confirmed_by');
        $registration->deposit = $request->input('deposit');
        $registration->notes = $request->input('notes');
        $registration->canceled_at = $request->input('canceled_at');
        $registration->arrived_at = $request->input('arrived_at');
        $registration->departed_at = $request->input('departed_at');

        $registration->room_id = $request->input('room_id');
        if ($registration->isDirty('event_id') && config('polanco.notify_registration_event_change')) {
            $finance_email = config('polanco.finance_email');
            $original_event = \App\Retreat::findOrFail($registration->getOriginal('event_id'));
            // dd($registration,$registration->event_id,$registration->getOriginal('event_id'));
            // return view('emails.registration-event-change', compact('registration', 'retreat', 'original_event'));
            try {
                Mail::to($finance_email)->send(new RegistrationEventChange($registration, $retreat, $original_event));
            } catch (\Exception $e) { //failed to send finance notification of event_id change on registration
                dd($e);
            }
        }

        $registration->save();

        return Redirect::action('PersonController@show', $registration->contact_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete-registration');

        $registration = \App\Registration::findOrFail($id);
        $retreat = \App\Retreat::findOrFail($registration->event_id);

        \App\Registration::destroy($id);
        $countregistrations = \App\Registration::where('event_id', '=', $registration->event_id)->count();
        //$retreat->attending = $countregistrations;
        $retreat->save();

        return Redirect::action('RegistrationController@index');
    }

    public function confirm($id)
    {
        $this->authorize('update-registration');

        $registration = \App\Registration::findOrFail($id);
        $registration->registration_confirm_date = \Carbon\Carbon::now();
        $registration->save();

        return redirect()->back();
    }

    public function attend($id)
    {
        $this->authorize('update-registration');
        $registration = \App\Registration::findOrFail($id);
        $registration->attendance_confirm_date = \Carbon\Carbon::now();
        $registration->save();

        return redirect()->back();
    }

    public function arrive($id)
    {
        $this->authorize('update-registration');
        $registration = \App\Registration::findOrFail($id);
        $registration->arrived_at = \Carbon\Carbon::now();
        $registration->save();

        return redirect()->back();
    }

    public function depart($id)
    {
        $this->authorize('update-registration');
        $registration = \App\Registration::findOrFail($id);
        $registration->departed_at = \Carbon\Carbon::now();
        $registration->save();

        return redirect()->back();
    }

    public function cancel($id)
    {
        $this->authorize('update-registration');
        $registration = \App\Registration::findOrFail($id);
        $registration->canceled_at = \Carbon\Carbon::now();
        $registration->save();

        return redirect()->back();
    }

    public function waitlist($id)
    {
        $this->authorize('update-registration');
        $registration = \App\Registration::findOrFail($id);
        $registration->status_id = config('polanco.registration_status_id.waitlist');
        $registration->save();

        return redirect()->back();
    }

    public function offwaitlist($id)
    {
        $this->authorize('update-registration');
        $registration = \App\Registration::findOrFail($id);
        $registration->status_id = config('polanco.registration_status_id.registered');
        $registration->save();

        return redirect()->back();
    }

    public function registrationEmail(Registration $participant)
    {
        $this->authorize('show-registration');

        // 1. Get a primary email address for participant.
        $primaryEmail = $participant->contact->primaryEmail()->first();

        if ($primaryEmail) {

            // 2. Setup infomration to be used with touchpoint for sending out registration email.
            $touchpoint = new \App\Touchpoint;
            $touchpoint->person_id = $participant->contact->id;
            $touchpoint->staff_id = config('polanco.self.id');
            $touchpoint->touched_at = Carbon::now();
            $touchpoint->type = 'Email';

            // 4. Only send out email if registration email is missing.
            $missingRegistrationEmail = $touchpoint->missingRegistrationEmail($participant->contact->id, $participant->retreat->idnumber);

            if ($missingRegistrationEmail) {
                try {
                    Mail::to($primaryEmail)->send(new RetreatRegistration($participant));
                } catch (\Exception $e) {
                    $touchpoint->notes = $participant->retreat->idnumber.' registration email failed.';
                }
                $touchpoint->notes = $participant->retreat->idnumber.' registration email sent.';
                $touchpoint->save();
            }
        }

        return redirect('person/'.$participant->contact->id);
    }

    public function confirmAttendance($token)
    {
        $registration = \App\Registration::where('remember_token', $token)->first();

        if ($registration) {
            $registration->registration_confirm_date = \Carbon\Carbon::now();
            $registration->remember_token = '';
            $registration->save();
        }

        return redirect()->away('https://montserratretreat.org/retreat-attendance');
    }
}
