@extends('layouts.master')
@section('content')
<h3>Create Country</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => 'CountryController@postStore', 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
    @include('admin.countries.shared.form')
{{ Form::close() }}
@stop
