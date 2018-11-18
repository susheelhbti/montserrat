@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-12">
        <h2>
            Organizations
            @can('create-contact')
                <span class="create">
                    <a href={{ action('OrganizationController@create') }}>
                        {!! Html::image('images/create.png', 'Create a Diocese',array('title'=>"Create Diocese",'class' => 'btn btn-light')) !!}
                    </a>
                </span>
            @endCan
        </h2>
        <p class="lead">{{$organizations->total()}} records</p>
        <form>
            <div class="form-row">
                <div class="col-3">
                    <select class="custom-select" id="org_type" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                        <option value="">Filter by type</option>
                        <option value="{{url('organization')}}">All organizations</option>
                        @foreach($subcontact_types as $subcontact_type=>$value)
                            <option value="{{url('organization/type/'.$value)}}">{{$subcontact_type}}</option>
                        @endForeach
                    </select>
                    {{-- JavaScript to keep the correct option selected on page reload --}}
                    <script>
                        var selectElement = document.querySelector('#org_type');
                        selectElement.childNodes.forEach(function(child) {
                            console.log(child);
                            if (child.value == window.location)
                                child.setAttribute('selected', ''); 
                        });
                    </script>
                    {{-- --}}
                </div>
            </div>
        </form>
    </div>
    <div class="col-12">
        @if ($organizations->isEmpty())
            <div class="col-12 text-center py-5">
                <p>No Organizations are currently in the database.</p>
            </div>
        @else
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Picture</th>
                    <th>Name</th> 
                    <th>Type</th>
                    <th>Address</th> 
                    <th>Main phone</th> 
                    <th>Email(s)</th> 
                    <th>Website(s)</th> 
               </tr>
            </thead>
            <tbody>
               @foreach($organizations as $organization)
                <tr>
                    <td>{!!$organization->avatar_small_link!!}</td>
                    <td><a href="{{url('organization/'.$organization->id)}}">{{ $organization->display_name }} </a></td>
                    <td>{{$organization->subcontact_type_label}}</td>
                    <td>
                        @foreach($organization->addresses as $address)
                        @if ($address->is_primary)
                        {!!$address->google_map!!} 
                        @endif
                        @endforeach
                    </td>
                    <td>
                        @if (!empty($organization->phone_main_phone_number)) 
                            <a href="tel:{{ $organization->phone_main_phone_number }}"> {{ $organization->phone_main_phone_number }} </a>
                        @else
                            N/A
                        @endIf
                    </td>
                    <td> 
                        <a href="mailto:{{ $organization->email_primary_text }}">{{ $organization->email_primary_text }}</a> 
                    </td>
                    <td>
                        
                        @foreach($organization->websites as $website)
                         @if(!empty($website->url))
                        <a href="{{$website->url}}" target="_blank">{{$website->url}}</a><br />
                        @endif
                        @endforeach
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@stop