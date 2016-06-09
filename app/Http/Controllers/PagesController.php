<?php

namespace montserrat\Http\Controllers;

use Illuminate\Http\Request;
use montserrat\Http\Requests;
use montserrat\Http\Controllers\Controller;

class PagesController extends Controller
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
    public function about()
    {
     return view('pages.about');   //
    }
    
    public function retreat()
    {
     return view('pages.retreat');   //
    }
    
    public function reservation()
    {
     return view('pages.reservation');   //
    }
    public function room()
    {
     return view('pages.room');   //
    }
   public function housekeeping()
    {
     return view('pages.housekeeping');   //
    }
    public function maintenance()
    {
     return view('pages.maintenance');   //
    }
    
    public function grounds()
    {
     return view('pages.grounds');   //
    }
    public function kitchen()
    {
     return view('pages.kitchen');   //
    }
    public function donation()
    {
     return view('pages.donation');   //
    }
    public function bookstore()
    {
     return view('pages.bookstore');   //
    }
    public function user()
    {
     return view('pages.user');   //
    }
    public function restricted()
    {
     return view('pages.restricted');   //
    }
    public function support()
    {
     return view('pages.support');   //
    }
    public function welcome()
    {
     return view('welcome');   //
    }
    public function retreatantinforeport($id)
    {
        $retreat = \montserrat\Retreat::where('idnumber','=',$id)->first();
        $registrations = \montserrat\Registration::where('retreat_id','=',$retreat->id)->with('retreat','retreatant.languages','retreatant.parish.contact_a.address_primary','retreatant.prefix','retreatant.suffix','retreatant.address_primary.state','retreatant.phones.location','retreatant.emails.location','retreatant.emergency_contact','retreatant.notes','retreatant.occupation')->get();
//dd($registrations);        
//$registrations = $registrations->sortBy($registrations->retreatant->lastname);
//products = Shop\Product::join('shop_products_options as po', 'po.product_id', '=', 'products.id')
  // ->orderBy('po.pinned', 'desc')
  //  ->select('products.*')       // just to avoid fetching anything from joined table
  // ->with('options')         // if you need options data anyway
  // ->paginate(5);
        return view('reports.retreatantinfo2',compact('registrations'));   //
    }
   public function retreatlistingreport($id)
    {
        $retreat = \montserrat\Retreat::where('idnumber','=',$id)->first();
        $registrations = \montserrat\Registration::where('retreat_id','=',$retreat->id)->with('retreat','retreatant')->get();
        

        return view('reports.retreatlisting',compact('registrations'));   //
    }
   public function retreatrosterreport($id)
    {
        $retreat = \montserrat\Retreat::where('idnumber','=',$id)->first();
        $registrations = \montserrat\Registration::where('retreat_id','=',$retreat->id)->with('retreat','retreatant.suffix','retreatant.address_primary','retreatant.prefix')->get();
        //dd($registrations);

        return view('reports.retreatroster',compact('registrations'));   //
    }
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
