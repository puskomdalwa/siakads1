<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">


<!--[if IE 8]>         <html class="ie8"> <![endif]-->
<!--[if IE 9]>         <html class="ie9 gt-ie8"> <![endif]-->
<!--[if gt IE 9]><!-->
{{-- <html class="gt-ie8 gt-ie9 not-ie">  --}}
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="author" content="Deddy Rusdiansyah,M.Kom">
    <meta name="description" content="Sistem Informasi Akademik Perguruan Tinggi.">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- Open Sans font from Google CDN -->
    <link
        href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&amp;subset=latin"
        rel="stylesheet" type="text/css">

    <!-- LanderApp's stylesheets -->
    <link href="{{ asset('assets/stylesheets/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/stylesheets/landerapp.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/stylesheets/widgets.min.css') }}" rel="stylesheet" type="text/css">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    {{-- scrollbar --}}
    {{-- <link rel="stylesheet" href="{{ asset('/css/simple-scrollbar.css') }}"> --}}

    <!-- Intro (Tourguide) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css" />

    <link href="{{ asset('assets/stylesheets/rtl.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/stylesheets/themes.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/stylesheets/mystyle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/stylesheets/loader.css') }}">

    @stack('css')

    <!--[if lt IE 9]>
 <script src="assets/javascripts/ie.min.js"></script>
<![endif]-->

    <style>
        .shepherd-theme-custom {
            width: 300px !important;
            padding-left: 10px !important;
            padding-right: 10px !important;
            padding-bottom: 10px !important;
            border: 2px solid var(--dalwaColor);
        }

        .shepherd-button-custom {
            background-color: var(--dalwaColor) !important;
        }
    </style>

    <style media="screen">
        .page-header {
            background-color: #173362 !important;
        }

        .page-header h1 {
            color: #fff;
        }

        .preloader {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-image: url("{{ asset('img/loading.gif') }}");
            background-repeat: no-repeat;
            background-color: #FFF;
            background-position: center;
        }
    </style>
    <style>
        #main-menu {
            height: 100vh;
            overflow: hidden;
        }

        .ss-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
            z-index: 1;
            float: left;
        }

        .scrollbar-thumb {
            background: rgb(0, 0, 0, .3) !important;
        }

        /* #content-wrapper {
            height: 100vh;
            overflow: hidden;
        } */

        .ss-wrapper {
            background-color: #fff;
        }
    </style>
</head>
