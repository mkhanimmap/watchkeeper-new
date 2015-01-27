@extends('layouts.master')
@section('content')
<h3>Role Edit</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => array('RoleController@postUpdate',$role->id), 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
    @include('admin.roles.shared.form', array('buttonText' => 'Update'))
{{ Form::close() }}
@stop
