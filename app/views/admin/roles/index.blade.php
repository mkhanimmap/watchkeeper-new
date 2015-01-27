@extends('layouts.master')
@section('content')
<h3>List of Roles</h3>
<h5> {{ link_to_route('admin.roles.create','Create',null, array('id'=> 'create-role', 'class' => 'btn  btn-xs btn-primary')) }}</h5>
<div class="row">
    <div class="col-sm-12">
    {{ pagination_links($roles)  }}
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th>{{ sort_by('name','Name','admin.roles.index') }}</th>
                <th>{{ sort_by('display_name','Display Name','admin.roles.index') }}</th>
                <th>Action</th>
            </tr>
        </thead>
        @foreach($roles as $role)
            <tr>
                <td> {{{ $role->name }}} </td>
                <td> {{{ $role->display_name }}}</td>
                <td>{{link_to_route('admin.roles.edit','Edit', array( 'id' => $role->id ),array('class' => 'btn btn-xs btn-success')) }}
                    {{link_to_route('admin.roles.get.permissions','Permissions',array('id' => $role->id),array('class' => 'btn btn-xs btn-primary')) }}</td>
            </tr>
        @endforeach
    </table>
    {{ pagination_links($roles)  }}
    </div>
<div>

@stop
