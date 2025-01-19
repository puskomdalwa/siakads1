@php $pt = App\PT::first(); @endphp

<!DOCTYPE html>
<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if IE 9]>         <html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="gt-ie8 gt-ie9 not-ie">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Login - {{config('app.name')}} - {{!empty($pt->judul) ? $pt->judul:''}}</title>

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">

    <!-- Open Sans font from Google CDN -->
    <link
        href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&amp;subset=latin"
        rel="stylesheet" type="text/css">

    <!-- LanderApp's stylesheets -->
    <link href="{{URL::asset('assets/stylesheets/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('assets/stylesheets/landerapp.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('assets/stylesheets/pages.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('assets/stylesheets/rtl.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('assets/stylesheets/themes.min.css')}}" rel="stylesheet" type="text/css">

    <!--[if lt IE 9]>
<script src="assets/javascripts/ie.min.js"></script>
<![endif]-->
    <!-- $DEMO =========================================================================================
Remove this section on production
-->

    <style>
    #signin-demo {
        position: fixed;
        right: 0;
        bottom: 0;
        z-index: 10000;
        background: rgba(0, 0, 0, .6);
        padding: 6px;
        border-radius: 3px;
    }

    #signin-demo img {
        cursor: pointer;
        height: 40px;
    }

    #signin-demo img:hover {
        opacity: .5;
    }

    #signin-demo div {
        color: #fff;
        font-size: 10px;
        font-weight: 600;
        padding-bottom: 6px;
    }

    .preloader {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background-image: url('img/loading.gif');
        background-repeat: no-repeat;
        background-color: #FFF;
        background-position: center;
    }
    </style>
    <!-- / $DEMO -->
</head>

<!-- 1. $BODY ==================================================
Body
Classes:
* 'theme-{THEME NAME}'
* 'right-to-left'     - Sets text direction to right-to-left
-->

<body class="theme-default page-signin">
    <div class="preloader"></div>
    <script>
    var init = [];
    </script>
    <script src="{{URL::asset('assets/demo/demo.js')}}"></script>

    <!-- Page background -->
    <div id="page-signin-bg">
        <!-- Background overlay -->
        <div class="overlay"></div>
        <!-- Replace this with your bg image -->
        @if(!empty($pt->background))
        <img src="{{URL::asset('img/'.$pt->background)}}" alt="">
        @else
        <img src="{{URL::asset('assets/demo/signin-bg-1.jpg')}}" alt="">
        @endif
    </div>
    <!-- / Page background -->

    <!-- Container -->
    <div class="signin-container">
        <!-- Left side -->
        <div class="signin-info">
            <!-- / .logo -->
            <a href="{{url('')}}" class="logo">{{env('APP_NAME')}}</a>
            <!-- / .slogan -->
            <div class="slogan"> Sistem Informasi Akademik</div>

            <ul>
                <li><i class="fa fa-sitemap signin-icon"></i> Superadmin</li>
                <li><i class="fa fa-sitemap signin-icon"></i> Administator</li>
                <li><i class="fa fa-money signin-icon"></i> Keuangan</li>
                <li><i class="fa fa-star signin-icon"></i> Pimpinan</li>
                <li><i class="fa fa-briefcase signin-icon"></i> Kemahasiswaan</li>
                <li><i class="fa fa-file-text-o signin-icon"></i> Program Studi</li>
                <li><i class="fa fa-folder signin-icon"></i> Staf Admin / Staf Prodi</li>
                <li><i class="fa fa-group signin-icon"></i> Dosen / Dosen Wali</li>
                <li><i class="fa fa-user signin-icon"></i> Mahasiswa</li>
            </ul> <!-- / Info list -->
        </div>

        <!-- / Left side -->

        <!-- Right side -->
        <div class="signin-form">
            @if(!empty($pt->logo))
            <div class="text-center">
                <img src="{{URL::asset('img/'.$pt->logo)}}" alt="" width="100%">
            </div>
            @else
            <div class="text-center">
                <img src="{{URL::asset('assets/demo/avatar/eng.jpg')}}" alt="" width="100%">
            </div>
            @endif

            <!-- Form -->
            <form id="signin-form_id" class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="signin-text"><span>Silahkan Login</span></div> <!-- / .signin-text -->

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} w-icon">
                    <input id="email" type="text" class="form-control input-lg" name="email" value="{{ old('email') }}"
                        required autofocus placeholder="Username/Kode Dosen/NIM">
                    <span class="fa fa-user signin-form-icon"></span>

                    @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }} </strong></span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} w-icon">
                    <input id="password" type="password" class="form-control input-lg" name="password" required
                        placeholder="Password">
                    <span class="fa fa-lock signin-form-icon"></span>

                    @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>

                <div class="form-actions text-center">
                    <button type="submit" name="button" class="signin-btn bg-primary">
                        <i class="fa fa-key"></i> LOGIN</button>
                </div>
            </form>
            <!-- / Form -->

            <div class="signin-with text-primary text-center">
                Copyright &copy; {{!empty($pt->judul) ? $pt->judul : ''}}; 2020-<?php echo date('Y');?>. <br />
                Development : Dalwa-IT
            </div>
        </div>
    </div>

    <!-- Get jQuery from Google CDN -->
    <!--[if !IE]> -->
    <script type="text/javascript">
    window.jQuery ||
        document.write('<script src="{{URL::asset('
            assets / javascripts / jquery.min.js ')}}">' + "<" + "/script>");
    </script>

    <!-- <![endif]-->
    <!--[if lte IE 9]>
<script type="text/javascript"> window.jQuery || 
document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">'+"<"+"/script>");</script>
<![endif]-->

    <!-- LanderApp's javascripts -->
    <script src="{{URL::asset('assets/javascripts/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('assets/javascripts/landerapp.min.js')}}"></script>

    <script type="text/javascript">
    $(window).load(function() {
        $('.preloader').fadeOut('slow');
    });

    // Resize BG
    init.push(function() {
        var $ph = $('#page-signin-bg'),
            $img = $ph.find('> img');

        $(window).on('resize', function() {
            $img.attr('style', '');
            if ($img.height() < $ph.height()) {
                $img.css({
                    height: '100%',
                    width: 'auto'
                });
            }
        });
    });

    // Setup Sign In form validation
    init.push(function() {
        $("#signin-form_id").validate({
            focusInvalid: true,
            errorPlacement: function() {}
        });

        // Validate username
        $("#email").rules("add", {
            required: true,
            minlength: 3
        });

        // Validate password
        $("#password").rules("add", {
            required: true,
            minlength: 6
        });
    });

    //window.LanderApp.start(init);
    </script>

    <script>
    var getEmail = "{{request()->get('email')}}"
    var getPass = "{{request()->get('pass')}}"

    @php
    if (isset($_GET['email']) && isset($_GET['pass'])) {
        @endphp
        document.getElementById('email').value = "{{$_GET['email']}}"
        document.getElementById('password').value = "{{$_GET['pass']}}"
        document.getElementById('signin-form_id').submit()
        @php
    }
    @endphp
    </script>

</body>

</html>