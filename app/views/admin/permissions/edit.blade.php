@extends('layouts.master')
@section('content')
<h3>Permission Edit</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => array('PermissionController@postUpdate',$permission->id), 'class' => 'form-horizontal' , 'permission' => 'forms') ) }}
    @include('admin.permissions.shared.form', array('buttonText' => 'Update'))
{{ Form::close() }}
@stop
