@extends('layouts.master')
@section('content')
<h3>Create Classification</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => 'ClassificationController@postStore', 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
    @include('admin.classifications.shared.form')
{{ Form::close() }}
@stop
