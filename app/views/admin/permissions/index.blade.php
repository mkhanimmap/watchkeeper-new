@extends('layouts.master')
@section('content')
@include('shared.errors-main')
<h3>List of Permissions</h3>
<h5> {{ link_to_route('admin.permissions.create','Create',null,  array('id'=> 'create-permission' , 'class' => 'btn  btn-xs btn-primary')) }}</h5>
{{ pagination_links($permissions)  }}
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>{{ sort_by('name','Name','admin.permissions.index') }}</th>
            <th>{{ sort_by('display_name','Display Name','admin.permissions.index') }}</th>
            <th>{{ sort_by('group_name','Group Name','admin.permissions.index') }}</th>
            <th>{{ sort_by('action_name','Action','admin.permissions.index') }}</th>
            <th>Action</th>
        </tr>
    </thead>
    @foreach($permissions as $permission)
        <tr>
            <td> {{{ $permission->name }}} </td>
            <td> {{{ $permission->display_name }}}</td>
            <td> {{{ $permission->group_name }}}</td>
            <td> {{{ $permission->action_name }}}</td>
            <td>
{{ link_to_route('admin.permissions.edit','Edit',array( 'id' => $permission->id), array( 'id'=> "edit_".$permission->name,  'class' => 'btn btn-xs btn-xs btn-success')) }}
{{ link_to_route('admin.permissions.destory','Delete',array( 'id' => $permission->id), array( 'id'=> "delete_".$permission->name , 'class' => 'btn btn-xs btn-danger')) }}
            </td>
        </tr>
    @endforeach
</table>
{{ pagination_links($permissions)  }}
@stop
