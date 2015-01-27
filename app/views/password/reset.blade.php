@extends('layouts.master')

@section('content')
    <h1>Set Your New Password</h1>
    <h1>Reset your password!</h1>
    @if($errors->has())
        @each('layouts.errors', $errors->all(), 'error', 'layouts.errors.empty')
    @endif
    @if (Session::has('error'))
        <p>{{ Session::get('error') }}</p>
    @endif
    {{ Form::open(array( 'url' => action("RemindersController@postReset") )) }}
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            {{ Form::label('email', 'Email Address:') }}
            {{ Form::email('email') }}
        </div>
        <div>
            {{ Form::label('password', 'Password:') }}
            {{ Form::password('password') }}
        </div>
        <div>
            {{ Form::label('password_confirmation', 'Password Confirmation:') }}
            {{ Form::password('password_confirmation') }}
        </div>
        <div>
            {{ Form::submit('Submit') }}
        </div>
    </form>
@stop