@extends('layouts.master')

@section('content')
    <h3>Login</h3>
    @include('shared.errors-main')
    {{ Form::open( array('route' => 'auth.login.post', 'class' => 'form-horizontal', 'role' => 'forms')) }}
         {{ Form::textField('username','Username', Input::old('username'), array('class'=>'form-control', 'placeholder'=>'Username' )) }}
         {{-- Form::textField('email','E-Mail', Input::old('email'), array('class'=>'form-control', 'placeholder'=>'example@sample.com' )) --}}
         {{ Form::passwordField('password','Password', array('class'=>'form-control', 'placeholder'=>'' )) }}
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {{ Form::submit('Login', array('class' => 'btn btn-login')) }}
            </div>
        </div>
    {{ Form::close() }}
@stop
