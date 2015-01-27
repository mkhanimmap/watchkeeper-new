@extends('layouts.master')
@section('content')
<h3>List of User Groups</h3>
<h5> {{ link_to_route('admin.usergroups.create','Create',null,  array('id'=> 'create-usergroup' , 'class' => 'btn  btn-xs btn-primary')) }}</h5>
{{ pagination_links($usergroups)  }}
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>{{ sort_by('code','Code','admin.usergroups.index') }}</th>
            <th>{{ sort_by('name','Name','admin.usergroups.index') }}</th>
            <th>Action</th>
        </tr>
    </thead>
    @foreach($usergroups as $usergroup)
        <tr>
            <td> {{{ $usergroup->code }}} </td>
            <td> {{{ $usergroup->name }}}</td>
            <td>{{ link_to_route('admin.usergroups.edit','Edit',array( 'id' => $usergroup->id ),array('class' => 'btn btn-xs btn-success')) }}</td>
        </tr>
    @endforeach
</table>
{{ pagination_links($usergroups)  }}
@stop
