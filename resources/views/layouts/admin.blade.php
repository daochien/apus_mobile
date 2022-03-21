<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    {{--CSRF Token--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="/images/favicon.png"/>
    <title>
        @section('title')
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
    <a href="" class="logo">
{{--        <img style="height: 50px;" src="{{ asset('images/logo_9pay.png') }}" alt="logo">--}}
        LOGO
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <div class="responsive_nav"></div>
            </a>
        </div>
        <div class="navbar-right toggle">
            <ul class="nav navbar-nav  list-inline">

                <li class="nav-item dropdown user user-menu">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <img src="{{ asset('images/authors/no_avatar.jpg') }}" alt="img" height="35px" width="35px" class="rounded-circle img-fluid float-left"/>
                        <div class="riot">
                            <div>
                                <p class="user_name_max">{{ auth()->user()->name ?? '' }}</p>
                                <span>
                                        <i class="caret"></i>
                                    </span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <img src="/images/authors/avatar3.png" alt="img" height="35px" width="35px" class="rounded-circle img-fluid float-left">

                            <p class="topprofiletext">{{ auth()->user()->name ?? '' }}</p>
                        </li>
                        <!-- Menu Body -->
                        <li>
                            <a href="">
                                <i class="livicon" data-name="user" data-s="18"></i>
                                My Profile
                            </a>
                        </li>
                        <li role="presentation"></li>
                        <li>
                            <a href="">
                                <i class="livicon" data-name="gears" data-s="18"></i>
                                Account Settings
                            </a>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="float-left">
                                <a href="">
                                    <i class="livicon" data-name="lock" data-size="16" data-c="#555555" data-hc="#555555" data-loop="true"></i>
                                    Lock
                                </a>
                            </div>
                            <div class="float-right">
                                <a href="{{ route('admin.logout') }}">
                                    <i class="livicon" data-name="sign-out" data-s="18"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </nav>
</header>
<div class="wrapper ">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side ">
        <section class="sidebar ">
            <div class="page-sidebar  sidebar-nav">
                <div class="nav_icons">
                    <ul class="sidebar_threeicons">
                        <li>
                            <a href="{{ URL::to('admin/advanced_tables') }}">
                                <i class="livicon" data-name="table" title="Advanced tables" data-loop="true"
                                   data-color="#418BCA" data-hc="#418BCA" data-s="25"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('admin/tasks') }}">
                                <i class="livicon" data-name="list-ul" title="Tasks" data-loop="true"
                                   data-color="#e9573f" data-hc="#e9573f" data-s="25"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('admin/gallery') }}">
                                <i class="livicon" data-name="image" title="Gallery" data-loop="true"
                                   data-color="#F89A14" data-hc="#F89A14" data-s="25"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('admin/users') }}">
                                <i class="livicon" data-name="user" title="Users" data-loop="true"
                                   data-color="#6CC66C" data-hc="#6CC66C" data-s="25"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
                <!-- BEGIN SIDEBAR MENU -->
            @include('layouts._left_menu')
            <!-- END SIDEBAR MENU -->
            </div>
        </section>
    </aside>
    <aside class="right-side">
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
<script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
@yield('footer_scripts')
</body>
</html>
