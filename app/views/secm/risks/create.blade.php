@extends('layouts.mobile')
@include('layouts.shared.mobile-form-header')
@section('content')
    @if($errors->has())
        <div class="ui-body ui-body-a ui-corner-all">
            @each('shared.errors-mobile', $errors->all(), 'error', 'layouts.errors.empty')
        </div>
    @endif
    <div data-role="content">
        {{ Form::open(array('action' => 'RiskController@postStore', 'class' => 'ui-body ui-body-a ui-corner-all' , 'data-ajax '=> "false") ) }}
            @include('secm.risks.shared.form')
        {{ Form::close() }}
    </div>
@stop
