<?php

namespace montserrat\Http\Controllers;

use Illuminate\Http\Request;
use montserrat\Http\Requests;
use montserrat\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Input;
use Auth;
use Spatie\GoogleCalendar\Event;

class RetreatsController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(Auth::User());
        $retreats = \montserrat\Retreat::whereDate('end_date', '>=', date('Y-m-d'))->orderBy('start_date','asc')->with('retreatmasters','innkeeper','assistant')->get();
        $oldretreats = \montserrat\Retreat::whereDate('end_date', '<', date('Y-m-d'))->orderBy('start_date','desc')->with('retreatmasters','innkeeper','assistant')->paginate(100);
        // $events = Event::get();
        // dd($events[4]);
        //dd($oldretreats);    
        return view('retreats.index',compact('retreats','oldretreats'));   //
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $retreat_house = \montserrat\Contact::with('retreat_directors.contact_b','retreat_innkeepers.contact_b','retreat_assistants.contact_b')->find(CONTACT_MONTSERRAT);
     
        foreach ($retreat_house->retreat_innkeepers as $innkeeper) {
            $i[$innkeeper->contact_id_b]=$innkeeper->contact_b->sort_name;
        }
        asort($i);
        $i=array(0=>'N/A')+$i;
        
        foreach ($retreat_house->retreat_directors as $director) {
            $d[$director->contact_id_b]=$director->contact_b->sort_name;
        }
        asort($d);
        $d=array(0=>'N/A')+$d;
        
        foreach ($retreat_house->retreat_assistants as $assistant) {
            $a[$assistant->contact_id_b]=$assistant->contact_b->sort_name;
        }
        asort($a);
        $a=array(0=>'N/A')+$a;
        
        return view('retreats.create',compact('d','i','a'));  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { // dd($request);
        $this->validate($request, [
            'idnumber' => 'required|unique:retreats',
            'start_date' => 'required|date|before:end',
            'end_date' => 'required|date|after:start',
            'title' => 'required',
            'innkeeper_id' => 'integer|min:0',
            'assistant_id' => 'integer|min:0',
            'year' => 'integer|min:1990|max:2020',
            'amount' => 'numeric|min:0|max:100000',
            'attending' => 'integer|min:0|max:150',
            'silent' => 'boolean'
          ]);
        
        $retreat = new \montserrat\Retreat;
        $retreat->idnumber = $request->input('idnumber');
        $retreat->start_date = $request->input('start');
        $retreat->end_date = $request->input('end');
        $retreat->title = $request->input('title');
        $retreat->description = $request->input('description');
        // TODO: create dropdown list of retreat types - disable for now
        $retreat->type = $request->input('type');
        // TODO: find a way to tag silent retreats, perhaps with event_type_id - for now disabled
        $retreat->silent = $request->input('silent');
        // amount will be related to default_fee_id?
        $retreat->amount = $request->input('amount');
        // attending should be calculated based on retreat participants
        // TODO: consider making Directors, Innkeepers, and Assistants participant roles and adding them by default to retreats
        $retreat->attending = $request->input('attending');
        $retreat->year = $request->input('year');
        $retreat->innkeeper_id = $request->input('innkeeper_id');
        $retreat->assistant_id = $request->input('assistant_id');
        $retreat->save();
        if (empty($request->input('directors')) or in_array(0,$request->input('directors'))) {
            $retreat->retreatmasters()->detach();
        } else {
            $retreat->retreatmasters()->sync($request->input('directors'));
        }
        
        return Redirect::action('RetreatsController@index');//
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $retreat = \montserrat\Retreat::with('retreatmasters','innkeeper','assistant')->find($id);
        $registrations = \montserrat\Registration::where('event_id','=',$id)->with('retreatant','retreatant.parish')->get();
       //dd($registrations); 
       return view('retreats.show',compact('retreat','registrations'));//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function edit($id)
    //{
    //   $retreats = \montserrat\Retreat::();
    //   return view('retreats.edit',compact('retreats'));
    //  }
public function edit($id)
    {
        //get this retreat's information
        $retreat = \montserrat\Retreat::with('retreatmasters','assistant','innkeeper')->find($id);

        //create lists of retreat directors, innkeepers, and assistants from relationship to retreat house 
        $retreat_house = \montserrat\Contact::with('retreat_directors.contact_b','retreat_innkeepers.contact_b','retreat_assistants.contact_b')->find(CONTACT_MONTSERRAT);
        
        foreach ($retreat_house->retreat_innkeepers as $innkeeper) {
            $i[$innkeeper->contact_id_b]=$innkeeper->contact_b->sort_name;
        }
        asort($i);
        $i=array(0=>'N/A')+$i;
        
        foreach ($retreat_house->retreat_directors as $director) {
            $d[$director->contact_id_b]=$director->contact_b->sort_name;
        }
        asort($d);
        $d=array(0=>'N/A')+$d;
        
        foreach ($retreat_house->retreat_assistants as $assistant) {
            $a[$assistant->contact_id_b]=$assistant->contact_b->sort_name;
        }
        asort($a);
        $a=array(0=>'N/A')+$a;
        
       //dd($a);
       return view('retreats.edit',compact('retreat','d','i','a'));
      }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
      // dd($request);
        $this->validate($request, [
            'idnumber' => 'required|unique:retreats,idnumber,'.$id,
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'title' => 'required',
            'innkeeper_id' => 'integer|min:0',
            'assistant_id' => 'integer|min:0',
            'year' => 'integer|min:1990|max:2020',
            'amount' => 'numeric|min:0|max:100000',
            'attending' => 'integer|min:0|max:150',
            'silent' => 'boolean'
        ]);
        
        $retreat = \montserrat\Retreat::findOrFail($request->input('id'));
        $retreat->idnumber = $request->input('idnumber');
        $retreat->start_date = $request->input('start_date');
        $retreat->end_date = $request->input('end_date');
        $retreat->title = $request->input('title');
        $retreat->description = $request->input('description');
        //$retreat->type = $request->input('type');
        //TODO: Figure out how to use event type or some other way of tracking the silent retreats, possibly silent boolean field in event table
        //$retreat->silent = $request->input('silent');
        //$retreat->amount = $request->input('amount');
        // attending field not needed - will calculate with count on registrations
        //$retreat->attending = $request->input('attending');
        //$retreat->year = $request->input('year');
        $retreat->innkeeper_id = $request->input('innkeeper_id');
        $retreat->assistant_id = $request->input('assistant_id');
        $retreat->save();
        
        if (empty($request->input('directors')) or in_array(0,$request->input('directors'))) {
            $retreat->retreatmasters()->detach();
        } else {
            $retreat->retreatmasters()->sync($request->input('directors'));
        }
       
        return Redirect::action('RetreatsController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       \montserrat\Retreat::destroy($id);
       return Redirect::action('RetreatsController@index');
       //
    }
}