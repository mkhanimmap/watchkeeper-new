@extends('layouts.master')
@section('content')
<h3>Create Role</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => 'RoleController@postStore', 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
    @include('admin.roles.shared.form')
{{ Form::close() }}
@stop
