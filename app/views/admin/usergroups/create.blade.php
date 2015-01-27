@extends('layouts.master')
@section('content')
<h3>Create Usergroup</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => 'UsergroupController@postStore', 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
   @include('admin.usergroups.shared.form')
{{ Form::close() }}
@stop
