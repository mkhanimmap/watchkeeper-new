@extends('layouts.master')

@section('content')
    <h1>Reset your password!</h1>
    @if($errors->has())
        @each('layouts.errors', $errors->all(), 'error', 'layouts.errors.empty')
    @endif
    @if (Session::has('error'))
        <p>{{ Session::get('error') }}</p>
    @elseif (Session::has('status'))
        <p>{{ Session::get('status') }}</p>
    @endif
    {{ Form::open(array( 'url' => action("RemindersController@postRemind") )) }}
        <div>
            {{ Form::label('email','Email Address:') }}
            {{ Form::text('email') }}
        </div>
        <div>
            {{ Form::submit('Reset') }}
        </div>
    {{ Form::close() }}
@stop
