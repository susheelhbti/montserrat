@extends('template')
@section('content')

    <section class="section-padding">
        <div class="jumbotron text-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>
                        <span class="grey">Touchpoint Index</span> 
                        <span class="grey">({{$touchpoints->total()}} records)</span> 
                        @can('update-touchpoint')
                            <span class="create">
                                <a href={{ action('TouchpointsController@create') }}>{!! Html::image('img/create.png', 'Add Touchpoint',array('title'=>"Add Touchpoint",'class' => 'btn btn-primary')) !!}</a>
                            </span>
                            <span class="create">
                                <a href={{ action('TouchpointsController@add_group',0) }}>{!! Html::image('img/group_add.png', 'Add Group Touchpoint',array('title'=>"Add Group Touchpoint",'class' => 'btn btn-primary')) !!}</a>
                            </span>
                    </h1>@endCan
                    <span>{!! $touchpoints->render() !!}</span>
                </div>
                @if ($touchpoints->isEmpty())
                    <p>It is a brand new world, there are no touchpoints!</p>
                @else
                <table class="table table-bordered table-striped table-hover"><caption><h2>Touchpoints</h2></caption>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Contact Name</th>
                            <th>Contacted by</th>
                            <th>Type</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($touchpoints as $touchpoint)
                        <tr>

                            <td style="width:17%"><a href="touchpoint/{{ $touchpoint->id}}">{{ date('M d, Y g:i A', strtotime($touchpoint->touched_at)) }}</a></td>
                            <td style="width:17%"><a href="person/{{ $touchpoint->person->id}}">{{ $touchpoint->person->full_name }}</a></td>
                            <td style="width:17%"><a href="person/{{ $touchpoint->staff->id}}">{{ $touchpoint->staff->display_name }}</a></td>
                            <td style="width:5%">{{ $touchpoint->type }}</td>
                            <td style="width:44%">{{ $touchpoint->notes }}</td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                    
                </table>
                {!! $touchpoints->render() !!}    
                    
                @endif
            </div>
        </div>
    </section>
@stop