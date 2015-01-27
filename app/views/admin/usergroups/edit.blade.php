@extends('layouts.master')
@section('content')
<h3>Usergroup Edit</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => array('UsergroupController@postUpdate',$usergroup->id), 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
    @include('admin.usergroups.shared.form', array('buttonText' => 'Update'))
{{ Form::close() }}
@stop
