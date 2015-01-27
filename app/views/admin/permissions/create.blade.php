@extends('layouts.master')
@section('content')
<h3>Create Permission</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => 'PermissionController@postStore', 'class' => 'form-horizontal' , 'permission' => 'forms') ) }}
    @include('admin.permissions.shared.form')
{{ Form::close() }}
@stop
