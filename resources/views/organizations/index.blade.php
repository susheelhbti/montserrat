@extends('template')
@section('content')



    <section class="section-padding">
        <div class="jumbotron text-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>
                    <span class="grey">Organization Index</span> 
                    <span class="create"><a href={{ action('OrganizationsController@create') }}>{!! Html::image('img/create.png', 'Create a Diocese',array('title'=>"Create Diocese",'class' => 'btn btn-primary')) !!}</a></span></h1>
                </div>
                @if ($organizations->isEmpty())
                    <p>No Organizations are currently in the database.</p>
                @else
                <table class="table table-bordered table-striped table-hover"><caption><h2>Organizations</h2></caption>
                    <thead>
                        <tr>
                            <th>Name</th> 
                            <th>Address</th> 
                            <th>Main phone</th> 
                            <th>Email(s)</th> 
                            <th>Website(s)</th> 
                       </tr>
                    </thead>
                    <tbody>
                       @foreach($organizations as $organization)
                        <tr>
                            <td><a href="organization/{{$organization->id}}">{{ $organization->display_name }} </a></td>
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
                    {!! $organizations->render() !!}    
                      
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </section>
@stop