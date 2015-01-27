@section('header')
<div data-role="header"class="ui-header ui-bar" role="banner">
    <h1 class="ui-title" role="heading" aria-level="1">{{{  $title  }}} </h1>
    <a href="{{ URL::route('secm.index') }}" class="ui-alt-icon ui-btn ui-shadow ui-corner-all ui-btn-a ui-icon-home ui-btn-icon-left">Home</a>
    @if (isset($urlCreate))
        <a href="{{ $urlCreate }}" class="ui-alt-icon ui-btn ui-shadow ui-corner-all ui-btn-a ui-icon-plus ui-btn-icon-left">Add</a>
    @endif
    {{--
        <a href="{{ URL::route('auth.sec.logout') }}" data-role="button" data-icon="star" data-theme="a" style="float:right;" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" class="ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all ui-btn-icon-left"><span class="ui-btn-inner"><span class="ui-btn-text">Logout</span><span class="ui-icon ui-icon-star ui-icon-shadow">&nbsp;</span></span></a>
    --}}
</div>
@stop
