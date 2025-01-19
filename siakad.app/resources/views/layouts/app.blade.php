@php $pt = App\PT::first(); @endphp

@include('layouts.header')

<body class="theme-default main-menu-animated page-profile page-search">
    <script>
    var init = [];
    </script>
    <script src="{{ asset('assets/demo/demo.js') }}"></script>
    @stack('demo')

    <div class="preloader"></div>
    <div id="main-wrapper">
        @include('layouts.main-navbar')
        @include('layouts.main-menu')

        <div id="content-wrapper">
            @include('layouts.breadcrumb')
            @include('layouts.page-header')

            <div class="row">
                @include('sweetalert::alert')
                @yield('content')
            </div>
        </div>

        <!-- <div id="main-menu-bg"></div> -->
    </div> <!-- / #main-wrapper -->

    @include('layouts.footer')