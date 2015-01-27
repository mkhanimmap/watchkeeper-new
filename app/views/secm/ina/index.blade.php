@extends('layouts.mobile')
@include('layouts.shared.mobile-header')
@section('content')
<form class="ui-mini">
<ul id="list" data-role="listview"  data-theme="a" data-divider-theme="b" class="touch ui-listview ui-group-theme-a" data-split-icon="delete" data-split-theme="a" data-inset="true">
    @foreach($incidents as $incident)
        <li>
            <a href="{{ URL::route('secm.ina.edit',$incident->id) }}" >
                <h2>{{ $incident->country->name }} </h2>
                <p>{{ $incident->timeDiffAgo }}</p>
                <p>{{ $incident->location}}</p>
                <p>{{ $incident->description}}</p>
            </a>
            <a href="{{ '#delete'.$incident->id }}" data-rel="popup" data-position-to="window" data-transition="pop">Delete item</a>
        </li>
    @endforeach
    @foreach($incidents  as $incident)
        <div data-role="popup" id="{{ 'delete'.$incident->id }}" data-theme="a" data-overlay-theme="b"  class="ui-content" style="max-width:340px; padding-bottom:2em;">
            <h3>Delete ?</h3>
            <p>You cannot get it back after deleted.</p>
            <a href="{{ URL::route('secm.ina.destroy', $incident->id) }}" data-ajax="false" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-delete ui-btn-icon-left ui-btn-inline ui-mini">Delete</a>
            <a href="#" data-rel="back" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini">Cancel</a>
        </div>
    @endforeach
</ul>
</form>
{{ $incidents->link() }}
@stop
