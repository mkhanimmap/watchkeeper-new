@extends('layouts.master')
@section('content')
<h3>Create User</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => 'UserController@postStore', 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
   @include('admin.users.shared.form',array('action' => 'create'))
{{ Form::close() }}
@stop
