@extends('layouts.master')

@section('script.header')
    @include('shared.googlemap-script')
@stop

@section('content')
<h3>Create Point</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => 'PointAreaController@postStore', 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
    @include('admin.pointareas.shared.form')
{{ Form::close() }}
@stop

@section('script.footer')
    @include('shared.pointarea-script')
@stop
