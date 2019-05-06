@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-12">
        <h2>
            Relationship Index
            @can('create-relatioship')
                <span class="options">
                    <a href={{ action('RelationshipController@create') }}>
                        <img src="{{ URL::asset('images/create.png') }}" alt="Add" class="btn btn-light" title="Add">
                    </a>
                </span>
            @endCan
        </h2>
    </div>
    <div class="col-12 my-3 table-responsive-md">
        @if ($relationships->isEmpty())
            <div class="col-12 text-center py-5">
                <p>It is a brand new world, there are no relatioships!</p>
            </div>
        @else
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Type</th>
                        <th scope="col">Contact A</th>
                        <th scope="col">Contact B</th>
                        <th scope="col">Start date</th>
                        <th scope="col">End date</th>
                        <th scope="col">Active</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($relationships as $relationship)
                    <tr>
                        <td><a href="relationship/{{ $relationship->id}}">{{ $relationship->id }}</a></td>
                        <td>{{ $relationship->relationship_type->name_a_b }}</td>
                        <td>{{ $relationship->contact_a->display_name }}</td>
                        <td>{{ $relationship->contact_b->display_name }}</td>
                        <td>{{ $relationship->start_date }}</td>
                        <td>{{ $relationship->end_date }}</td>
                        <td>{{ $relationship->is_active }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        {!! $relationships->render() !!}
    </div>
</div>
@stop
