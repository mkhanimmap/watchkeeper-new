@extends('layouts.master')
@section('script.header')
@include('shared.googlemap-script')
@stop
@section('content')
<h3>Edit Point</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => array('PointAreaController@postUpdate',$pointarea->id), 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
    @include('admin.pointareas.shared.form', array('buttonText' => 'Update'))
{{ Form::close() }}
@stop
@section('script.footer')
    @include('shared.pointarea-script')
@stop
