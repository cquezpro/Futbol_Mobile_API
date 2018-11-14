<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0072)http://tutsplus.github.io/a-simple-responsive-html-email/HTML/index.html -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>A Simple Responsive HTML Email</title>
    <style type="text/css">
        body {margin: 0; padding: 0; min-width: 100%!important;}
        img {height: auto;}
        .content {width: 100%; max-width: 600px;}
        .header {padding: 40px 30px 20px 30px;}
        .innerpadding {padding: 15px 30px 30px 30px;}
        .borderbottom {border-bottom: 1px solid #f2eeed;}
        .subhead {font-size: 15px; color: #ffffff; font-family: sans-serif; letter-spacing: 10px;}
        .h1, .h2, .bodycopy {color: #153643; font-family: sans-serif;}
        .h1 {font-size: 33px; line-height: 38px; font-weight: bold;}
        .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
        .bodycopy {font-size: 16px; line-height: 22px;}
        .button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px; color: #ffffff; text-decoration: none;}
        .footer {padding: 20px 30px 15px 30px;}
        .footercopy {font-family: sans-serif; font-size: 14px; color: #9A9A9A; text-decoration: none;}
        .footercopy span { color: #e05443; }

        @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
            body[yahoo] .hide {display: none!important;}
            body[yahoo] .buttonwrapper {background-color: transparent!important;}
            body[yahoo] .button {padding: 0px!important;}
            body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
            body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
        }

        /*@media only screen and (min-device-width: 601px) {
          .content {width: 600px !important;}
          .col425 {width: 425px!important;}
          .col380 {width: 380px!important;}
          }*/

    </style>
</head>

<body yahoo="" bgcolor="#f6f8f1" style="" cz-shortcut-listen="true">
<table width="100%" bgcolor="#FCFCFC" border="0" cellpadding="0" cellspacing="0" style="padding:30px 0px;">
    <tbody><tr>
        <td>
            <!--[if (gte mso 9)|(IE)]>
            <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>
            <![endif]-->
            <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px;">
                <tbody><tr>
                    <td bgcolor="#fff" class="header">
                        <table width="520" align="left" border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                            <tr>
                                <td height="70" style="padding: 0 20px 20px 0;" class="borderbottom">
                                    <img class="fix" src="{{ $message->embed(public_path('images/logo_fc.png')) }}" width="300" height="70" border="0" alt="">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="innerpadding">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody><tr>
                                <td class="h2">
                                    Bienvenido!!
                                </td>
                            </tr>
                            <tr>
                                <td class="bodycopy">
                                    Hola, {{ $user->full_name }}:
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="col380" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                        <tbody><tr>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tbody><tr>
                                                        <td class="bodycopy">
                                                            {!! $description  !!}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 20px 0 0 0;">
                                                            <table class="buttonwrapper" bgcolor="#F36B2A" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody><tr>
                                                                    <td class="button" height="45">
                                                                        {!! $pswOrCode !!}
                                                                    </td>
                                                                </tr>
                                                                </tbody></table>
                                                        </td>
                                                    </tr>
                                                    </tbody></table>
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </td>
                            </tr>
                            </tbody></table>
                    </td>
                </tr>
                <tr>
                    <td class="footer" bgcolor="#FCFCFC">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody><tr>
                                <td align="center" class="footercopy">
                                    Se envió este mensaje a <span>{{ $user->email }}</span> por pedido suyo.<br>
                                    FutbolConnect
                                </td>
                            </tr>
                            </tbody></table>
                    </td>
                </tr>
                </tbody></table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    </tbody></table>
</body>
</html>
