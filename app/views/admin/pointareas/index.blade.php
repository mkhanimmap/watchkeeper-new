@extends('layouts.master')
@section('content')
<h3>List of Points</h3>
<h5> {{ link_to_route('admin.pointareas.create','Create',null,  array('id'=> 'create-pointareas', 'class' => 'btn  btn-xs btn-primary')) }}</h5>
@if ($pointareas->count() > 0)
    {{ pagination_links($pointareas)  }}
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th>{{ sort_by('name','Name','admin.pointareas.index') }}</th>
                <th>{{ sort_by('description','Dscription','admin.pointareas.index') }}</th>
                <th>Action</th>
            </tr>
        </thead>
        @foreach($pointareas as $pointarea)
            <tr>
                <td> {{{ $pointarea->name }}} </td>
                <td> {{{ $pointarea->description }}}</td>
                <td>{{ link_to_route('admin.pointareas.edit','Edit',array( 'id' => $pointarea->id ), array('class' => 'btn btn-xs btn-success')) }}</td>
            </tr>
        @endforeach
    </table>
    {{ pagination_links($pointareas)  }}
@else
    <p>No Records !!</p>
@endif
@stop
