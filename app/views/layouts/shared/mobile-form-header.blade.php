@section('header')
<div data-role="header" data-theme="b">
    @if (isset($urlIndex))
        <a href="{{ $urlIndex }}" class="ui-alt-icon ui-btn ui-shadow ui-corner-all ui-btn-a ui-icon-arrow-l ui-btn-icon-left">Back</a>
    @endif
    <h3>{{{  $title  }}} </h3>
</div>
@stop

