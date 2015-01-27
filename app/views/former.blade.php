@extends('layouts.master')
@section('content')
{{ Former::horizontal_open()->action( action( 'UserController@postUpdate',$user->id ) ) }}
    {{ Former::populate( $user ) }}
    <?php isset($user->username) ? ($username = $user->username) : ($username = '') ;  ?>
    <?php isset($user->email) ? ($email = $user->email) : ($email = '') ;  ?>
    {{ Form::hidden('username', isset($user->username) ? \Crypt::encrypt($username . "|" . rand()) : '') }}
    {{ Form::hidden('email', isset($user->username) ? \Crypt::encrypt($email . "|" . rand()) : '') }}
    <h4> {{{ isset($user->username) ? $user->username : '' }}} </h4>
    <h4> {{{ isset($user->username) ? $user->email : '' }}} </h4>
    {{ Former::label('First name') }}
    {{ Former::text('firstname') }}
    {{ Former::text('middlename')->name('Middle Name') }}
    {{ Former::text('lastname')->name('Last Name') }}
    {{  Former::actions()
    ->large_primary_submit('Submit')
    ->large_inverse_reset('Reset') }}
{{ Former::close() }}

<div class="form-group">
    {{ Form::label('code_a2','Code A2',array('class' => 'col-sm-2 control-label') )}}
    <div class="col-sm-10">
        {{ Form::text('code_a2', isset($country->code_a2) ? $country->code_a2 : '', array('class'=>'form-control', 'placeholder'=>'A2')) }}
    </div>
</div>
@stop
