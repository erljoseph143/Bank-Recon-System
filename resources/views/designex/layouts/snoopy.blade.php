<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{{ $title }}</title>
    <meta name="description" content="Snoopy is a Dashboard & Admin Site Responsive Template by hencework." />
    <meta name="keywords" content="admin, admin dashboard, admin template, cms, crm, Snoopy Admin, Snoopyadmin, premium admin templates, responsive admin, sass, panel, software, ui, visualization, web app, application" />
    <meta name="author" content="hencework"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('snoopy/favicon.ico') }}">
    <link rel="icon" href="{{ asset('snoopy/favicon.ico') }}" type="image/x-icon">
    @yield('styles')
    <!-- Custom CSS -->
    <link href="{{ asset('snoopy/dist/css/style.css') }}" rel="stylesheet" type="text/css">
    @yield('endstyles')
</head>

<body>
<!-- Preloader -->
<div class="preloader-it">
    <div class="la-anim-1"></div>
</div>
<!-- /Preloader -->
<div class="wrapper  theme-1-active pimary-color-blue">
    <!-- Top Menu Items -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="mobile-only-brand pull-left">
            <div class="nav-header pull-left">
                <div class="logo-wrap">
                    <a href="{{ route('designex.dashboard') }}">
                        <img class="brand-img" src="{{ asset('snoopy/dist/img/logo.png') }}" alt="brand"/>
                        <span class="brand-text">Designex</span>
                    </a>
                </div>
            </div>
            <a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block ml-20 pull-left" href="javascript:void(0);"><i class="zmdi zmdi-menu"></i></a>
            <a id="toggle_mobile_nav" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-more"></i></a>
        </div>
        @include('designex.layouts.snoopy-topnav')
    </nav>
    <!-- /Top Menu Items -->
    <!-- Left Sidebar Menu -->
    @include('designex.layouts.snoopy-sidebar')
    <!-- /Left Sidebar Menu -->
    <!-- Right Setting Menu -->
    <div class="setting-panel">
        <ul class="right-sidebar nicescroll-bar pa-0">
            <li class="layout-switcher-wrap">
                <ul>
                    <li>
                        <span class="layout-title">Scrollable header</span>
                        <span class="layout-switcher">
								<input type="checkbox" id="switch_3" class="js-switch"  data-color="#8BC34A" data-secondary-color="#dedede" data-size="small"/>
							</span>
                        <h6 class="mt-30 mb-15">Theme colors</h6>
                        <ul class="theme-option-wrap">
                            <li id="theme-1"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-2"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-3"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-4"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-5" class="active-theme"><i class="zmdi zmdi-check"></i></li>
                            <li id="theme-6"><i class="zmdi zmdi-check"></i></li>
                        </ul>
                        <button id="reset_setting" class="btn  btn-warning btn-xs btn-outline btn-rounded mb-10">reset</button>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <button id="setting_panel_btn" class="btn btn-warning btn-circle setting-panel-btn shadow-2dp"><i class="zmdi zmdi-settings"></i></button>
    <!-- /Right Setting Menu -->
    <!-- Right Sidebar Backdrop -->
    <div class="right-sidebar-backdrop"></div>
    <!-- /Right Sidebar Backdrop -->
    <!-- Main Content -->
    <div class="page-wrapper">
        <div class="container-fluid pt-10">
            <!-- Row -->
            <div class="row">
                @yield('content')
            </div>
            <!-- /Row -->
        </div>
        <!-- Footer -->
        <footer class="footer container-fluid pl-30 pr-30">
            <div class="row">
                <div class="col-sm-12">
                    <p>2018 &copy; DesignX. Pampered by KoyThemes</p>
                </div>
            </div>
        </footer>
        <!-- /Footer -->
    </div>
    <!-- /Main Content -->
</div>
<!-- /#wrapper -->
<!-- JavaScript -->
<!-- jQuery -->
<script src="{{ asset('snoopy/vendors/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('snoopy/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Slimscroll JavaScript -->
<script src="{{ asset('snoopy/dist/js/jquery.slimscroll.js') }}"></script>
<!-- Switchery JavaScript -->
<script src="{{ asset('snoopy/vendors/bower_components/switchery/dist/switchery.min.js') }}"></script>
@yield('scripts')
<!-- Init JavaScript -->
<script src="{{ asset('snoopy/dist/js/init.js') }}"></script>
{{--<script src="{{ asset('snoopy/dist/js/dashboard-data.js') }}"></script>--}}
@yield('endscripts')
<script>
    $("#logout-app").click(function(){
        document.getElementById('logout-form').submit();
    });
</script>
</body>
</html>