<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
        .footer {
            position: absolute;
            bottom: 0;
            width: 644px;
            height: 20px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <center><img src="http://210.56.8.110:8585/sec/sec-m/images/banner_email.jpg"></center>
    <div>&nbsp;</div>
    <table cellpadding="0" cellspacing="0" width="100%">
        <tbody>
            <tr>
                <td>
                    <div>
                        <h2 style="font-family:trebuchet MS;font-size:14pt;color:#953735;font-weight:bold">
                            <strong>iMMAP Threat HQ - {{ immap_to_datetime_string($threat->threat_datetime) }}</strong>
                        </h2>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <h2 style="font-family:trebuchet MS;font-size:12pt;color:#953735;font-weight:bold;margin-bottom:0px">CURRENT THREAT EVENT</h2>
    <hr style="margin-top:0px" align="center" noshade="" size="7" width="100%">
    <table style="border-collapse:collapse" cellpadding="0" cellspacing="0" width="100%">
        <p style="font-family:trebuchet MS;font-size:10pt;margin-bottom:0px;margin-top:0px"><strong>{{ $threat->country->name}} - {{ $threat->location }}</strong><br />
            {{ $threat->description }}<br >
            <strong>Advice :</strong> <p style="font-family:trebuchet MS;font-size:10pt;margin-bottom:0px;margin-top:0px"> {{ $threat->advice}} </p> <br >
            <strong>Source : </strong>
            @if (filter_var(rtrim(ltrim($threat->source)), FILTER_VALIDATE_URL) !== false)
            {{ HTML::link($threat->source)}}
            @else
            {{ $threat->source }}
            @endif
        </p>
        <table style="width:100%" border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td style="font-family:trebuchet MS;border-top:1px solid #808080" align="center" width="100%">
                        <p style="font-size:10pt;font-weight:bold;text-decoration:underline;margin-bottom:0px">FOR INTERNAL iMMAP USE ONLY</p>
                        <p style="font-size:8pt;margin-top:0px">Do not distribute further.  The information contained in this email is confidential and privileged against disclosure except for internal use by the recipient</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </table>
</body>
</html>
