@extends('layouts.master')
@section('content')
<h3>User Edit</h3>
@include('shared.errors-main')
{{ Form::open( array( 'action' => array( 'UserController@postUpdate',$user->id ), 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
    @include('admin.users.shared.form', array('buttonText' => 'Update','action' => 'edit'))
{{ Form::close() }}
@stop
