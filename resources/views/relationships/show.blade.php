@extends('template')
@section('content')

<div class="row bg-cover" style='margin:0;'>
    <div class="col-12">
        @can('update-relationship')
            <h1>
                Relationship details: <strong><a href="{{url('relationship/'.$relationship->id.'/edit')}}">{{ $relationship->relationship_type->name_a_b }}</a></strong>
            </h1>
        @else
            <h1>
                Relationship details: <strong>{{$relationship->relationship_type->name_a_b}}</strong>
            </h1>
        @endCan
    </div>
    <div class="col-12 mb-4">
        @can('update-relationship')
            <a href="{{ action('RelationshipController@edit', $relationship->id) }}" class="btn btn-light">Update relationship</a>
        @endcan
        @can('delete-relationship')
            {!! Form::open(['method' => 'DELETE', 'route' => ['relationship.destroy', $relationship->id], 'onsubmit'=>'return ConfirmDelete()', 'class' => 'd-inline']) !!}
                {!! Form::submit('Delete relationship', ['class'=>'btn btn-danger']) !!}
            {!! Form::close() !!}
        @endcan
    </div>
    <div class="col-12" style="padding-left 15px;">
        <h5>Type: {{$relationship->name}}</h5>
        <h5>Contact A: {!!$relationship->contact_a->contact_link!!}</h5>
        <h5>Contact B: {!!$relationship->contact_b->contact_link!!}</h5>
        <h5>Start date: {{$relationship->start_date}}</h5>
        <h5>End date: {{$relationship->end_date}}</h5>
        <h5>Active: {{$relationship->is_active}}</h5>
    </div>
</div>
@stop
