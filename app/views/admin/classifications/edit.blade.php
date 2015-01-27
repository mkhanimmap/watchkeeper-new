@extends('layouts.master')
@section('content')
<h3>Edit Classfication</h3>
@include('shared.errors-main')
{{ Form::open( array('action' => array('ClassificationController@postUpdate',$classification->id), 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
    @include('admin.classifications.shared.form', array('buttonText' => 'Update'))
{{ Form::close() }}
@stop
