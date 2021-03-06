<?php

namespace App;

use Carbon\Carbon;
use Html;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Retreat extends Model
{
    /*
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;

    protected $table = 'event';

    protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at', 'disabled_at', 'deleted_at'];  //

    public function setStartDateAttribute($date)
    {
        $this->attributes['start_date'] = Carbon::parse($date);
    }

    public function setEndDateAttribute($date)
    {
        $this->attributes['end_date'] = Carbon::parse($date);
    }

    public function getRegistrationCountAttribute()
    {
        // keep in mind that if/when innkeeper and other not retreatant roles are added will not to use where clause to keep the count accurate and exclude non-participating participants
        return $this->registrations->count();
    }

    public function getRetreatantCountAttribute()
    {
        // keep in mind that if/when innkeeper and other not retreatant roles are added will not to use where clause to keep the count accurate and exclude non-participating participants
        return $this->retreatants->count();
    }

    public function getRetreatantWaitlistCountAttribute()
    {
        // keep in mind that if/when innkeeper and other not retreatant roles are added will not to use where clause to keep the count accurate and exclude non-participating participants
        return $this->retreatants_waitlist->count();
    }

    public function assistant()
    {   // TODO: evaluate whether the assumption that this is an individual makes a difference, currently retreat factory will force individual to avoid undefined variable on retreat.show
        return $this->belongsTo(Contact::class, 'assistant_id', 'id')->whereContactType(config('polanco.contact_type.individual'));
    }

    public function captains()
    {
        // TODO: handle with participants of role Retreat Director or Master - be careful with difference between (registration table) retreat_id and (participant table) event_id
        return $this->belongsToMany(Contact::class, 'captain_retreat', 'event_id', 'contact_id')->whereContactType(config('polanco.contact_type.individual'));
    }

    public function innkeeper()
    {   // TODO: evaluate whether the assumption that this is an individual makes a difference, currently retreat factory will force individual to avoid undefined variable on retreat.show

        return $this->belongsTo(Contact::class, 'innkeeper_id', 'id')->whereContactType(config('polanco.contact_type.individual'));
    }

    public function event_type()
    {
        return $this->hasOne(EventType::class, 'id', 'event_type_id');
    }

    public function retreatmasters()
    {
        // TODO: handle with participants of role Retreat Director or Master - be careful with difference between (registration table) retreat_id and (participant table) event_id
        return $this->belongsToMany(Contact::class, 'retreatmasters', 'retreat_id', 'person_id')->whereContactType(config('polanco.contact_type.individual'));
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'event_id', 'id');
    }

    public function retreatants()
    {
        return $this->registrations()->whereCanceledAt(null);
    }

    public function retreatants_waitlist()
    {
        return $this->registrations()->whereCanceledAt(null)->whereStatusId(config('polanco.registration_status_id.waitlist'));
    }

    public function getEmailRegisteredRetreatantsAttribute()
    {
        $bcc_list = '';
        foreach ($this->registrations as $registration) {
            if ($registration->status_id == config('polanco.registration_status_id.registered')) {
                if (! empty($registration->retreatant->email_primary_text) && is_null($registration->canceled_at)) {
                    $bcc_list .= $registration->retreatant->email_primary_text.',';
                }
            }
        }

        return 'mailto:?bcc='.$bcc_list;
    }

    public function getEmailWaitlistRetreatantsAttribute()
    {
        $bcc_list = '';
        foreach ($this->registrations as $registration) {
            if ($registration->status_id == config('polanco.registration_status_id.waitlist')) {
                if (! empty($registration->retreatant->email_primary_text) && is_null($registration->canceled_at)) {
                    $bcc_list .= $registration->retreatant->email_primary_text.',';
                }
            }
        }

        return 'mailto:?bcc='.$bcc_list;
    }

    public function getRetreatTypeAttribute()
    {
        //dd($this->event_type);
        if (isset($this->event_type)) {
            return $this->event_type->name;
        } else {
            return;
        }
    }

    public function getRetreatNameAttribute()
    {
        //dd($this->event_type);
        if (isset($this->title)) {
            return $this->title;
        } else {
            return;
        }
    }

    public function getRetreatScheduleLinkAttribute()
    {
        if (Storage::has('event/'.$this->id.'/schedule.pdf')) {
            $img = Html::image('images/schedule.png', 'Schedule', ['title'=>'Schedule']);
            $link = '<a href="'.url('retreat/'.$this->id.'/schedule" ').'class="btn btn-default" style="padding: 3px;">'.$img.'</a>';

            return $link;
        } else {
            return;
        }
    }

    public function getRetreatContractLinkAttribute()
    {
        if (Storage::has('event/'.$this->id.'/contract.pdf')) {
            $img = Html::image('images/contract.png', 'Contract', ['title'=>'Contract']);
            $link = '<a href="'.url('retreat/'.$this->id.'/contract" ').'class="btn btn-default" style="padding: 3px;">'.$img.'</a>';

            return $link;
        } else {
            return;
        }
    }

    public function getRetreatEvaluationsLinkAttribute()
    {
        if (Storage::has('event/'.$this->id.'/evaluations.pdf')) {
            $img = Html::image('images/evaluation.png', 'Evaluations', ['title'=>'Evaluations']);
            $link = '<a href="'.url('retreat/'.$this->id.'/evaluations" ').'class="btn btn-default" style="padding: 3px;">'.$img.'</a>';

            return $link;
        } else {
            return;
        }
    }

    public function getRetreatTeamAttribute()
    {
        $team = '';
        $directors = $this->retreatmasters()->get();
        //dd($directors);
        foreach ($directors as $director) {
            // dd($director);
            $team .= $director->last_name.'(D) ';
        }
        $innkeeper = $this->innkeeper()->first();
        //dd($innkeeper->last_name);
        if (isset($innkeeper->last_name)) {
            $team .= $innkeeper->last_name.'(I) ';
        }
        $assistant = $this->assistant()->first();
        if (isset($assistant->last_name)) {
            $team .= $assistant->last_name.'(A) ';
        }

        return $team;
    }

    /*
     * Returns an array of attendee email addresses to be added to a Google Calendar event
     * see https://developers.google.com/google-apps/calendar/create-events (for PHP section)
     *  'attendees' => array(
            array('email' => 'lpage@example.com'),
            array('email' => 'sbrin@example.com'),
        )
     */
    public function getRetreatAttendeesAttribute()
    {
        $attendees = [];
        $directors = $this->retreatmasters()->get();
        //dd($directors);
        foreach ($directors as $director) {
            if (! empty($director->email_primary->email)) {
                array_push($attendees, ['email'=>$director->email_primary->email]);
            }
        }
        $innkeeper = $this->innkeeper()->first();
        //dd($innkeeper->last_name);
        if (! empty($innkeeper->email_primary->email)) {
            array_push($attendees, ['email'=>$innkeeper->email_primary->email]);
        }
        $assistant = $this->assistant()->first();
        if (! empty($assistant->email_primary->email)) {
            array_push($attendees, ['email'=>$assistant->email_primary->email]);
        }

        return $attendees;
    }

    public function scopeType($query, $event_type_id)
    {
        return $query->where('event_type_id', $event_type_id);
    }
}
