@extends('template')
@section('content')

<section class="section-padding">
    <div class="jumbotron text-left">
        <div class="panel panel-default">
            <div class="panel-heading">
                <span><h2>Touch point details with <a href="../person/{{$touchpoint->person_id}}">
                    {{ isset($touchpoint->person->firstname) ? $touchpoint->person->firstname : null }} 
                    {{ isset($touchpoint->person->lastname) ? $touchpoint->person->lastname : null}} 
                    {{ (!empty($touchpoint->person->nickname)) ? "(&quot;$touchpoint->person->nickname&quot;)" : null }}</a>
                </span>                
            </div>
            
            <div class='row'>
                <div class='col-md-4'>
                        <strong>Date: </strong>{{$touchpoint->touched_at}}
                        <br /><strong>Contacted by: </strong>{{$touchpoint->staff->lastname}}, {{$touchpoint->staff->firstname}}  
                        <br /><strong>Type: </strong>{{$touchpoint->type}}     
                        <br /><strong>Notes: </strong>{{$touchpoint->notes}}
                    
                </div>
            </div></div>
            <div class='row'>
                <div class='col-md-1'><a href="{{ action('TouchpointsController@edit', $touchpoint->id) }}" class="btn btn-info">{!! Html::image('img/edit.png', 'Edit',array('title'=>"Edit")) !!}</a></div>
                <div class='col-md-1'>{!! Form::open(['method' => 'DELETE', 'route' => ['touchpoint.destroy', $touchpoint->id]]) !!}
                {!! Form::image('img/delete.png','btnDelete',['class' => 'btn btn-danger','title'=>'Delete']) !!} 
                {!! Form::close() !!}</div><div class="clearfix"> </div>
            </div>
        
    </div>
</section>
@stop