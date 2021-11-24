<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title> </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta content="text/html;charset=utf-8" http-equiv="content-type">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
        <style>
            body{letter-spacing: 1px; font-family: 'Source Sans Pro', sans-serif;}
            a{text-decoration: none;color:inherit}
            .header_category{text-transform:uppercase;}
        </style>

    </head>
    <body style="background-color: #e6dfdd; margin: 0 auto; width:960px">
        <table  align="center" bgcolor="#ffffff" cellpadding="10px">
            @include('emails.header')
            @yield('content')
            <tr>
                <td colspan="8" align="center">
                    FOLLOW US
                    <hr style="width: 90px;height:4px;background:#000000">
                    @foreach($sociallink as $sl)
                        <a href="{{$sl['url']}}"><img src="{{asset('components/_front/images/sociallink')}}/{{$sl['image']}}"></a>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td colspan="8" align="center">
                    <br>
                    Contact our Customer Team at {{$email_config['web_email']}}
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    <table width="100%">
                        <tr>
                            <td align="right" width="50%" style="padding-right:50px;border-right:1px solid #000000;color:#ba0f13;">
                                <a href="{{url('contact-us')}}">Questions about your order?</a>
                            </td>
                            <td align="left" width="50%" style="padding-left: 50px">
                                We will not share your personal details with other parties.<br>
                                <a href="{{url('pages/Privacy-Policy')}}"><b>Click</b></a>
                                to see out privacy Policy
                            </td>
                        </tr>
                    </table>
                </td>

            </tr>
            <tr>
                <td align="center" colspan="8">
                    <br><br>
                    <a href="{{url('/')}}"><b>WWW.MAYONETTE.COM</b></a><br>
                    &copy;2015.Mayonette.All rights reserved.
                </td>
            </tr>
        </table>
    </body>
</html>