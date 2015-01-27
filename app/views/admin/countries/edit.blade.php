@extends('layouts.master')
@section('content')
<h3>Country Edit</h3>
@include('shared.errors-main')

{{ Form::open( array('action' => array('CountryController@postUpdate',$country->id), 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
    @include('admin.countries.shared.form', array('buttonText' => 'Update'))
{{ Form::close() }}
@stop
