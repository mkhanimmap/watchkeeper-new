<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{ HTML::style("css/bower_components/bootstrap/dist/css/bootstrap-superhero.css") }}
    {{ HTML::style("css/bower_components/bootstrap/dist/css/bootstrap-chosen.css") }}
    {{ HTML::style("css/bower_components/bootstrap/dist/css/bootstrap-custom-button.css") }}
    {{ HTML::style("css/immap-nonresponsive.css") }}
    {{ HTML::style("css/immap-admin.css") }}
    {{ HTML::script('css/bower_components/jquery/jquery-1.11.0.js') }}
    {{ HTML::script('css/bower_components/bootstrap/dist/js/bootstrap.js') }}
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('script.header')
    <title> {{ $title or 'WatchKeeper'}}</title>
    </head>
    <body>
        <div class="header">
            <div class="headerinner">
                <div class="oasislogo">
                    {{ HTML::decode(link_to('admin/', HTML::image('css/images/oasislogo.png', 'IMMAP - Because Infomation Matters'))) }};
                </div>
                <div class="topbar clearfix"> Watchkeeper </div>
            </div>
        </div>
        {{-- start to develop new navigation bar instead of left vertical bar --}}
        <div class="navbar navbar-default" role="navigation">
            @include('layouts.shared.navbar')
        </div>
        <div class="container">
          <div id="main" class="row">
            <div class="clearfix visible-xs"></div>
            <div id="content"  class="col-xs-12">
                @yield('content')
            </div>
        </div> {{-- main --}}
    </div>
</body>
@yield('script.footer')
</html>
