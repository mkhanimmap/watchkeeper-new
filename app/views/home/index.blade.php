@extends('layouts.home')
@section('script.header')
    {{ HTML::style("js/jqwidgets/styles/jqx.base.css") }}
    {{ HTML::style("js/jqwidgets/styles/jqx.metro.css") }}
    {{ HTML::script('js/jqwidgets/jqxcore.js') }}
    {{ HTML::script('js/jqwidgets/jqxbuttons.js') }}
    {{ HTML::script('js/jqwidgets/jqxscrollbar.js') }}
    {{ HTML::script('js/jqwidgets/jqxpanel.js') }}
    {{ HTML::script('js/jqwidgets/jqxlistbox.js') }}
    <style type="text/css">
    .panelHeaderCustom {
    background: linear-gradient(#3C3C3C, #111111) repeat scroll 0 0 #111111;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#3C3C3C', endColorstr='#111111') repeat scroll 0 0 #111111;
    background: -webkit-gradient(linear, left top, left bottom, from(#3C3C3C), to(#111111)) repeat scroll 0 0 #111111;
    border: 1px solid #2A2A2A;
    color: #FFFFFF;
    font-weight: bold;
    text-shadow: 0 -1px 1px #000000;
    margin-top: 0px;
    height: 25px;
    text-align: center;
    font-family: "Gill Sans MT","Trebuchet MS","Verdana";
    }

    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            // Create jqxPanel
            function getTheme() {

                var theme =  $.data(document.body, 'theme');
                if (theme == null || theme == undefined) theme = 'classic';


                var themestart = window.location.toString().indexOf('?');
                // if (themestart == -1) {
                    // return '';
                // }

                var theme = window.location.toString().substring(1 + themestart);

                theme = 'metro';
                // theme = 'office';
                var url = "js/jqwidgets/styles/v." + theme + '.css';
                return theme;
            };
            var theme = getTheme();
            $("#jqxWidget").jqxPanel({ 'width': 500, 'height': 450, 'theme':theme});
        });
    </script>
@stop
@section('content')
<div id='jqxWidget'>
    <!--header-->
    <h5 class="panelHeaderCustom">{{ "Current Security Advisories" }}</h5>
    <!--Content-->
       <div style='white-space: nowrap;'>
           <ul>
               @foreach($securityAdvisories as $securityAdvisory)
                    <li>
                        <h5> {{ $securityAdvisory->country->name }} ({{ $securityAdvisory->incidentType->name }}) </h5>
                        <p>{{ $securityAdvisory->location}}</p>
                        <p>{{ $securityAdvisory->description}}</p>
                        <p>{{ $securityAdvisory->advice}}</p>
                    </li>
               @endforeach
           </ul>
       </div>
       <!--header-->
       <h5 class="panelHeaderCustom">{{ "Current Threat Warnings" }}</h5>
       <!--Content-->
        <div style='white-space: nowrap;'>
            <ul>
                @foreach($threats as $threat)
                    <li>
                        <h5> <b>{{ $threat->country_code }} {{ $threat->title}}</b>  ({{ $threat->threat_type_name }}) </h5>
                    </li>
                @endforeach
            </ul>
        </div>
</div>
</div>
@stop
