@extends('template')
@section('content')



    <section class="section-padding">
        <div class="jumbotron text-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>
                    <span class="grey">Room Schedules for {{$dts[0]->format('F d, Y')}} - {{$dts[31]->format('F d, Y')}} </span> 
                    </div>
                
                @if (empty($m))
                    <p> Yikes, there is nothing to schedule!</p>
                @else
                <table border="1" class="table">
                        <caption><h2>Legend: 
                            <span style="background-color:yellowgreen">A=Available</span>; 
                            <span style="background-color:yellow">R=Reserved</span>; 
                            <span style="background-color:yellow">O=Occupied</span>; 
                            <span style="background-color:lightcoral">C=Cleaning Needed</span>; 
                            <span style="background-color:lightcoral">M=Maintenance Required</span>
                        </h2></caption>
                    <thead>
                        <tr>
                            <th>Building</th>
                            <th>Room#</th>
                            @foreach($dts as $dt)
                            <th>{{$dt->day}}</th>
                            @endforeach
                        </tr>                   
                    </thead>
                    <tbody>
                        @if ($roomsort->isEmpty())
                            <p> Yikes, there are no rooms!</p>
                        @else
                            
                            @foreach($roomsort as $room)
                        
                            <tr>
                                <td>{{$room->location->name}}</td> 
                                <td>{{$room->name}}</td>
                                
                                @foreach($dts as $dt)
                                    @if ($m[$room->id][$dt->toDateString()]['status'] == 'R')
                                    <td class='warning'>
                                    {!! Html::link('registration/'.$m[$room->id][$dt->toDateString()]['registration_id'], $m[$room->id][$dt->toDateString()]['status'] , array('title'=>$m[$room->id][$dt->toDateString()]['retreatant_name'])) !!} 
                                    @else
                                    <td class='success'>
                                        A
                                    @endif
                                    </td>
                                @endforeach
                                
                                
                                <!--
                                @if ($dt->day == 21) 
                                    <td class="warning">R</td>
                                 @elseif ($dt->day == 22 or $room->name == "104") 
                                    <td class="danger">M</td>
                                 @elseif ($dt->day == 23) 
                                    <td class="warning">O</td>
                                 @elseif ($dt->day == 25) 
                                    <td class="danger">C</td>
                                 @else 
                                    <td class="success">A</td>
                                 @endif -->
                            @endforeach
                            </tr>

                            
                        @endif
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </section>
@stop