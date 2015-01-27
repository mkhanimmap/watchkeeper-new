@extends('layouts.master')
@section('content')
<h3>List of Users</h3>
<h5> {{ link_to_route('admin.users.create','Create',null,  array('id'=> 'create-user' , 'class' => 'btn  btn-xs btn-primary')) }}</h5>
{{ pagination_links($users)  }}
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>{{ sort_by('username','Username','admin.users.index') }}</th>
            <th>{{ sort_by('firstname','Name','admin.users.index') }}</th>
            <th>{{ sort_by('email','E-mail','admin.users.index') }}</th>
            <th>Action</th>
        </tr>
    </thead>
    @foreach($users as $user)
        <tr>
            <td> {{{ $user->username }}} </td>
            <td> {{{ $user->firstname . ' ' . $user->middlename . ' ' . $user->lastname }}}</td>
            <td> {{{ $user->email }}}</td>
            <td>{{ link_to_route('admin.users.edit','Edit',array( 'id' => $user->id ),array('class' => 'btn btn-xs btn-success')) }}
            {{ link_to_route('admin.users.get.role','Roles',array( 'id' => $user->id), array('class' => 'btn btn-xs btn-primary')) }}
            {{ link_to_route('admin.users.get.country','Countries',array( 'id' => $user->id), array('class' => 'btn btn-xs btn-primary')) }} </td>
        </tr>
    @endforeach
</table>
{{ pagination_links($users)  }}
@stop
