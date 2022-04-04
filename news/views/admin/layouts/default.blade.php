<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    {{--CSRF Token--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @section('title')
            | Schultes
        @show
    </title>

    <!-- global css -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"/>
    <!-- end of global css -->

    <!--page level css-->
    @yield('header_styles')
    <!--end of page level css-->

<body class="skin-josh">
<header class="header">
    <a href="{{ route('admin.dashboard') }}" class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="logo">
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <div class="responsive_nav"></div>
            </a>
        </div>
    </nav>
</header>
<div class="wrapper ">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side ">
        <section class="sidebar ">
            <div class="page-sidebar  sidebar-nav">                
                <div class="clearfix"></div>
                <!-- BEGIN SIDEBAR MENU -->
                @include('admin.layouts._left_menu')
                <!-- END SIDEBAR MENU -->
            </div>
        </section>
    </aside>
    <aside class="right-side">

        <!-- Notifications -->
        <div id="notific">
        @include('notifications')
        </div>

                <!-- Content -->
        @yield('content')

    </aside>
    <!-- right-side -->
</div>
<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Return to top"
   data-toggle="tooltip" data-placement="left">
    <i class="livicon" data-name="plane-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
</a>
<!-- global js -->

<script src="{{ asset('js/admin.js') }}" type="text/javascript"></script>




<!-- end of global js -->
<!-- begin page level js -->
@yield('footer_scripts')
        <!-- end page level js -->
</body>
</html>
