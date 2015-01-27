<!doctype html>
<html>
<head>
    <title>{{{ 'Security' }}}</title>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0"/>
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css"/>
    {{ HTML::style("css/immap-secm.css") }}
    @include('shared.googlemap-script')
    <!-- <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script> -->
    @yield('script.header')
    {{ HTML::script('css/bower_components/jquery/jquery-1.11.0.js') }}
    @include('shared.jqm-showhide-script')
    <script src="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
</head>
<body>
<div class="ui-loader ui-corner-all ui-body-a ui-loader-default">
    <span class="ui-icon ui-icon-loading"></span><h1>Loading...</h1>
</div>
<div data-role="page" id= "{{{ $mainpageid or "mainpage"  }}}" data-theme="b">
    @yield('header')
    @yield('content')
</div>
</body>
@yield('script.footer')
</html>
