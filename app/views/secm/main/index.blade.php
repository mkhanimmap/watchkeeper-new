@extends('layouts.mobile')
@include('layouts.shared.mobile-header')
@section('content')
<form class="ui-mini">
    <ul data-role="listview" data-inset="true" class="ui-listview ui-listview-inset ui-corner-all ui-shadow">
        <li class="ui-first-child" ><a href="{{ URL::route('secm.ina.index') }}" class="ui-btn ui-btn-icon-right ui-icon-carat-r">iMMAP Incident Alerts</a></li>
        <li data-wrapperels="div"  ><a href="{{ URL::route('secm.poi.index') }}" class="ui-btn ui-btn-icon-right ui-icon-carat-r">iMMAP Point of Incident</a></li>
        <li data-wrapperels="div"  ><a href="{{ URL::route('secm.security-advisory.index') }}" class="ui-btn ui-btn-icon-right ui-icon-carat-r">iMMAP Security Advisory</a></li>
        <li data-wrapperels="div"  ><a href="{{ URL::route('secm.threat.index') }}" class="ui-btn ui-btn-icon-right ui-icon-carat-r">iMMAP Threat Event</a></li>
        <li class="ui-last-child" ><a href="{{ URL::route('secm.risk.index') }}" class="ui-btn ui-btn-icon-right ui-icon-carat-r">iMMAP Risk Level and Movement State</a></li>
    </ul>
</form>
@stop
