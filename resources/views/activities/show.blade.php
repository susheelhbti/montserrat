@extends('template')
@section('content')

<section class="section-padding">
    <div class="jumbotron text-left">
        <div class="panel panel-default">
            <div class="panel-heading">
                <span>
                    <h2>
                        @can('update-activity')
                            <a href="{{url('activity/'.$activity->id.'/edit')}}">Activity details</a> 
                        @else
                            Activity details
                        @endCan
                        for {!!$activity->targets_full_name_link!!}
                </span>                
            </div>
            
            <div class='row'>
                <div class='col-md-4'>
                        <strong>Date: </strong>{{$activity->touched_at}}
                        <br /><strong>Type: </strong>{{$activity->activity_type_label}}     
                        <br /><strong>Assignee(s): </strong>{!! $activity->assignees_full_name_link !!}  
                        <br /><strong>Person(s) Contacted: </strong>{!! $activity->targets_full_name_link !!}  
                        <br /><strong>Subject: </strong>{{$activity->subject or 'N/A'}}
                        <br /><strong>Notes: </strong>{{$activity->details or 'N/A'}}
                        <br /><strong>Medium: </strong>{{$activity->medium_label}}
                        <br /><strong>Status: </strong>{{$activity->status_label}}
                        <br /><strong>Duration: </strong>{{$activity->duration}}
                        <br /><strong>Priority: </strong>{{$activity->priority_label}}
                        <br /><strong>Location: </strong>{{$activity->location or 'N/A'}}
                        <br /><strong>Created by: </strong>{!! $activity->sources_full_name_link !!}  
                        <br /><strong>Parent: </strong>{{$activity->parent_id or 'N/A'}}
                        <br /><strong>Original: </strong>{{$activity->original_id or 'N/A'}}
                </div>
            </div></div>
            <div class='row'>
                @can('update-activity')
                    <div class='col-md-1'>
                        <a href="{{ action('ActivitiesController@edit', $activity->id) }}" class="btn btn-info">{!! Html::image('img/edit.png', 'Edit',array('title'=>"Edit")) !!}</a>
                    </div>
                @endCan
                @can('delete-activity')
                    <div class='col-md-1'>
                        {!! Form::open(['method' => 'DELETE', 'route' => ['activity.destroy', $activity->id],'onsubmit'=>'return ConfirmDelete()']) !!}
                        {!! Form::image('img/delete.png','btnDelete',['class' => 'btn btn-danger','title'=>'Delete']) !!} 
                        {!! Form::close() !!}
                    </div>
                @endCan
                <div class="clearfix"> </div>
            </div>
    </div>
</section>
@stop