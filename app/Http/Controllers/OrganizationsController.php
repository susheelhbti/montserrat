<?php

namespace montserrat\Http\Controllers;

use Illuminate\Http\Request;
use montserrat\Http\Requests;
use montserrat\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Input;



class OrganizationsController extends Controller
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
        //TODO: subcontact_type dependent on order in database which is less than ideal really looking for where not a parish or diocese organization 
        //$organizations = \montserrat\Contact::whereContactType(CONTACT_TYPE_ORGANIZATION)->where('subcontact_type','>',CONTACT_TYPE_DIOCESE)->orderBy('organization_name', 'asc')->with('addresses.state','phone_primary.location','emails','websites','bishops.contact_b','parishes.contact_a')->toSql();
        $organizations = \montserrat\Contact::organizations_generic()->orderBy('organization_name', 'asc')->paginate(100);
        
        //dd($organizations);
        
        return view('organizations.index',compact('organizations'));   //
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = \montserrat\StateProvince::orderby('name')->whereCountryId(COUNTRY_ID_USA)->pluck('name','id');
        $states->prepend('N/A',0); 
        
        $countries = \montserrat\Country::orderby('iso_code')->pluck('iso_code','id');
        $countries->prepend('N/A',0); 
        
        $default['state_province_id'] = STATE_PROVINCE_ID_TX;
        $default['country_id'] = COUNTRY_ID_USA;
                
        $subcontact_types = \montserrat\ContactType::whereIsReserved(FALSE)->whereIsActive(TRUE)->pluck('label','id');
        $subcontact_types->prepend('N/A',0); 

      
        return view('organizations.create',compact('subcontact_types','states','countries','default'));  
    
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
            $this->validate($request, [
                'organization_name' => 'required',
                'subcontact_type' => 'integer|min:0',
                'email_main' => 'email',
                'website_main' => 'url',
                'phone_main_phone' => 'phone',
                'phone_main_fax' => 'phone',
            ]);
            
        $organization = new \montserrat\Contact;
        $organization->organization_name = $request->input('organization_name');
        $organization->display_name  = $request->input('organization_name');
        $organization->sort_name  = $request->input('organization_name');
        $organization->contact_type = CONTACT_TYPE_ORGANIZATION;
        $organization->subcontact_type = $request->input('subcontact_type');
        $organization->save();
        
        $organization_address= new \montserrat\Address;
            $organization_address->contact_id=$organization->id;
            $organization_address->location_type_id=LOCATION_TYPE_MAIN;
            $organization_address->is_primary=1;
            $organization_address->street_address=$request->input('street_address');
            $organization_address->supplemental_address_1=$request->input('supplemental_address_1');
            $organization_address->city=$request->input('city');
            $organization_address->state_province_id=$request->input('state_province_id');
            $organization_address->postal_code=$request->input('postal_code');
            $organization_address->country_id=$request->input('country_id');  
        $organization_address->save();
        
        $organization_main_phone= new \montserrat\Phone;
            $organization_main_phone->contact_id=$organization->id;
            $organization_main_phone->location_type_id=LOCATION_TYPE_MAIN;
            $organization_main_phone->is_primary=1;
            $organization_main_phone->phone=$request->input('phone_main_phone');
            $organization_main_phone->phone_type='Phone';
        $organization_main_phone->save();
        
        $organization_fax_phone= new \montserrat\Phone;
            $organization_fax_phone->contact_id=$organization->id;
            $organization_fax_phone->location_type_id=LOCATION_TYPE_MAIN;
            $organization_fax_phone->phone=$request->input('phone_main_fax');
            $organization_fax_phone->phone_type='Fax';
        $organization_fax_phone->save();
        
        $organization_email_main = new \montserrat\Email;
            $organization_email_main->contact_id=$organization->id;
            $organization_email_main->is_primary=1;
            $organization_email_main->location_type_id=LOCATION_TYPE_MAIN;
            $organization_email_main->email=$request->input('email_main');
        $organization_email_main->save();
        
        $organization_website_main = new \montserrat\Website;
            $organization_website_main->contact_id=$organization->id;
            $organization_website_main->url=$request->input('website_main');
            $organization_website_main->website_type='Main';
        $organization_website_main->save();
        
        //TODO: add contact_id which is the id of the creator of the note
        if (!empty($request->input('note'))); {
            $organization_note = new \montserrat\Note;
            $organization_note->entity_table = 'contact';
            $organization_note->entity_id = $organization->id;
            $organization_note->note=$request->input('note');
            $organization_note->subject='Organization Note';
            $organization_note->save();
        }
        
   
return Redirect::action('OrganizationsController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $organization = \montserrat\Diocese::with('bishop')->findOrFail($id);
        $organization = \montserrat\Contact::with('addresses.state','addresses.location','phones.location','emails.location','websites','notes','phone_main_phone.location')->findOrFail($id);
       //dd($organization); 
       return view('organizations.show',compact('organization'));//
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // TODO: make create and edit bishop id multi-select with all bishops defaulting to selected on edit
        // TODO: consider making one primary bishop with multi-select for seperate auxilary bishops (new relationship)
        $organization = \montserrat\Contact::with('address_primary.state','address_primary.location','phone_main_phone.location','phone_main_fax.location','email_primary.location','website_main','notes')->findOrFail($id);

        $states = \montserrat\StateProvince::orderby('name')->whereCountryId(COUNTRY_ID_USA)->pluck('name','id');
        $states->prepend('N/A',0); 
        
        $countries = \montserrat\Country::orderby('iso_code')->pluck('iso_code','id');
        $countries->prepend('N/A',0); 
        
        $default['state_province_id'] = STATE_PROVINCE_ID_TX;
        $default['country_id'] = COUNTRY_ID_USA;
        
        $subcontact_types = \montserrat\ContactType::whereIsReserved(FALSE)->whereIsActive(TRUE)->pluck('label','id');
        $subcontact_types->prepend('N/A',0); 
        
        //dd($organization);
              
       return view('organizations.edit',compact('organization','states','countries','default','subcontact_types'));
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

        $this->validate($request, [
            'organization_name' => 'required',
            'bishop_id' => 'integer|min:0',
            'email_main' => 'email',
            'website_main' => 'url',
            'phone_main_phone' => 'phone',
            'phone_main_fax' => 'phone',
        ]);

        $organization = \montserrat\Contact::with('address_primary.state','address_primary.location','phone_main_phone.location','phone_main_fax.location','email_primary.location','website_main','note_organization')->findOrFail($id);
        $organization->organization_name = $request->input('organization_name');
        $organization->display_name = $request->input('organization_name');
        $organization->sort_name  = $request->input('sort_name');
        $organization->contact_type = CONTACT_TYPE_ORGANIZATION;
        $organization->subcontact_type = $request->input('subcontact_type');
        $organization->save();
      
        if (empty($organization->address_primary)) {
            $address_primary = new \montserrat\Address;
        } else {
            $address_primary = \montserrat\Address::findOrNew($organization->address_primary->id);
        }
        $address_primary->contact_id=$organization->id;
        $address_primary->location_type_id=LOCATION_TYPE_MAIN;
        $address_primary->is_primary=1;
            
        $address_primary->street_address = $request->input('street_address');
        $address_primary->supplemental_address_1 = $request->input('supplemental_address_1');
        $address_primary->city = $request->input('city');
        $address_primary->state_province_id = $request->input('state_province_id');
        $address_primary->postal_code = $request->input('postal_code');
        $address_primary->country_id = COUNTRY_ID_USA;
        $address_primary->is_primary = 1;
        $address_primary->save();
//        dd($organization->phone_main_phone);
        if (empty($organization->phone_main_phone)) {
            $phone_primary = new \montserrat\Phone;
        } else {
            $phone_primary = \montserrat\Phone::findOrNew($organization->phone_main_phone->id);
        
        }
        $phone_primary->contact_id=$organization->id;
        $phone_primary->location_type_id=LOCATION_TYPE_MAIN;
        $phone_primary->is_primary=1;
        $phone_primary->phone=$request->input('phone_main_phone');
        $phone_primary->phone_type='Phone';
        $phone_primary->save();
        
        if (empty($organization->phone_main_fax)) {
                $phone_main_fax = new \montserrat\Phone;
            } else {
                $phone_main_fax = \montserrat\Phone::findOrNew($organization->phone_main_fax->id);
        }
        $phone_main_fax->contact_id=$organization->id;
        $phone_main_fax->location_type_id=LOCATION_TYPE_MAIN;
        $phone_main_fax->phone=$request->input('phone_main_fax');
        $phone_main_fax->phone_type='Fax';
        $phone_main_fax->save();
        
        if (empty($organization->email_primary)) {
            $email_primary = new \montserrat\Email;
        } else {
            $email_primary = \montserrat\Email::findOrNew($organization->email_primary->id);
        }
        $email_primary->contact_id=$organization->id;
        $email_primary ->is_primary=1;
        $email_primary ->location_type_id=LOCATION_TYPE_MAIN;
        $email_primary ->email=$request->input('email_primary');
        $email_primary->save();
        
        if (empty($organization->website_main)) {
            $website_main = new \montserrat\Website;
        } else {
            $website_main = \montserrat\Website::findOrNew($organization->website_main->id);
        }
        $website_main->url = $request->input('website_main');
        $website_main->contact_id=$organization->id;
        $website_main->website_type='Main';
        $website_main->save();
        
        if (empty($organization->note_organization)) {
            $organization_note = new \montserrat\Note;
        } else {
            $organization_note = \montserrat\Note::findOrNew($organization->note_organization->id);
        }
        $organization_note->entity_table = 'contact';
        $organization_note->entity_id = $organization->id;
        $organization_note->note=$request->input('note');
        $organization_note->subject='Organization Note';
        $organization_note->save();
        
        return Redirect::action('OrganizationsController@index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO: delete addresses, emails, webpages, and phone numbers for persons, parishes, dioceses and organizations
         \montserrat\Contact::destroy($id);
        return Redirect::action('OrganizationsController@index');
    }
}
