<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <link rel="shortcut icon" href="{{ asset('admin/minton/assets/images/favicon_1.ico') }}">

    <title>{{ $title }} @yield('mode')</title>

    <link href="{{ asset('admin/minton/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    @stack('styles')
    {{--<link href="{{ asset('admin/minton/plugins/custombox/dist/custombox.min.css') }}" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ asset('admin/assets/plugins/custombox-4.0.3/dist/custombox.min.css') }}">
    <link href="{{ asset('admin/minton/assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/minton/assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <script src="{{ asset('admin/minton/assets/js/modernizr.min.js') }}"></script>

</head>


<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
    <div class="topbar">

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="text-center">
                <a href="{{ url('admin/home') }}" class="logo"><i class="mdi mdi-radar"></i> <span>BRS Admin</span></a>
            </div>
        </div>

        <!-- Button mobile view to collapse sidebar menu -->
        <nav class="navbar-custom">

            <ul class="list-inline float-right mb-0">

                <li class="list-inline-item notification-list hide-phone">
                    <a class="nav-link waves-light waves-effect" href="#" id="btn-fullscreen">
                        <i class="mdi mdi-crop-free noti-icon"></i>
                    </a>
                </li>

                <li class="list-inline-item dropdown notification-list">
                    <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                       aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('admin/minton/assets/images/users/avatar-1.jpg') }}" alt="user" class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="text-overflow"><small>Welcome ! {{ $login_user_firstname }}</small> </h5>
                        </div>

                        @include('admin.layouts.topnav')

                    </div>
                </li>

            </ul>

            <ul class="list-inline menu-left mb-0">
                <li class="float-left">
                    <button class="button-menu-mobile open-left waves-light waves-effect">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </li>
                {{--<li class="hide-phone app-search">--}}
                {{--<form role="search" class="">--}}
                {{--<input type="text" placeholder="Search..." class="form-control">--}}
                {{--<a href="#"><i class="fa fa-search"></i></a>--}}
                {{--</form>--}}
                {{--</li>--}}
            </ul>

        </nav>

    </div>
    <!-- Top Bar End -->


    <!-- ========== Left Sidebar Start ========== -->

    <div class="left side-menu">
        <div class="sidebar-inner slimscrollleft" style="overflow: inherit; width: auto; height: 602px;">
            <!--- Divider -->
            <div id="sidebar-menu">
                <ul>
                    <li class="menu-title">Main</li>

                    @include('admin.layouts.sidebar')
                </ul>

                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">

                <!-- Page-Title -->
                <div class="row">

                    <div class="col-sm-12">

                        <div class="page-title-box">

                            <h4 class="page-title">{{ $pagetitle }} @yield('mode')</h4>

                            <ol class="breadcrumb float-right">

                                @yield('crumb')

                            </ol>

                            <div class="clearfix"></div>

                        </div>

                    </div>

                </div>
                {{--table content--}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="portlet">
                            <div class="portlet-heading portlet-default">
                                <h3 class="portlet-title">{{ $doctitle }} @yield('badge')</h3>
                                <div class="portlet-widgets">

                                    @yield('top-buttons')

                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div id="bg-default" class="panel-collapse collapse show">
                                <div class="portlet-body">
                                    @yield('table-nav')
                                    <div id="reload-table">
                                        @yield('content')
                                    </div>
                                    <div class="pagination-data">
                                        @yield('pagination')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet">
                            <div class="portlet-heading portlet-default">
                                <h3 class="portlet-title">{{ $doctitle }} @yield('badge2')</h3>
                                <div class="portlet-widgets">

                                    @yield('top-buttons2')

                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div id="bg-default" class="panel-collapse collapse show">
                                <div class="portlet-body">
                                    @yield('table-nav2')
                                    <div id="reload-table">
                                        @yield('content2')
                                    </div>
                                    <div class="pagination-data">
                                        @yield('pagination2')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- end container -->
        </div>
        <!-- end content -->

        <footer class="footer">
            2016 - {{ date('Y') }} © AGC Programmers <span class="hide-phone">- Anonymous.ph</span>
        </footer>

    </div>
    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->

</div>
<!-- END wrapper -->
@yield('modal')
<meta name="_token" content="{!! csrf_token() !!}" />
<script>
    var resizefunc = [];
</script>

<!-- Plugins  -->
<script src="{{ asset('admin/minton/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('admin/minton/assets/js/popper.min.js') }}"></script><!-- Popper for Bootstrap -->
<script src="{{ asset('admin/minton/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/minton/assets/js/detect.js') }}"></script>
<script src="{{ asset('admin/minton/assets/js/fastclick.js') }}"></script>
<script src="{{ asset('admin/minton/assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('admin/minton/assets/js/jquery.blockUI.js') }}"></script>
<script src="{{ asset('admin/minton/assets/js/waves.js') }}"></script>
<script src="{{ asset('admin/minton/assets/js/wow.min.js') }}"></script>
<script src="{{ asset('admin/minton/assets/js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('admin/minton/assets/js/jquery.scrollTo.min.js') }}"></script>
{{--<!-- Required for menu toggling -->--}}

{{--<!-- A fancy on and off button  -->--}}
{{--<script src="{{ asset('admin/minton/plugins/switchery/switchery.min.js') }}"></script>--}}

{{--<!-- A tiny chart  -->--}}
{{--<script src="{{ asset('admin/minton/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>--}}

{{--<!-- plugin for cloud animation weather -->--}}
{{--<!-- skycons -->--}}
{{--<script src="{{ asset('admin/minton/plugins/skyicons/skycons.min.js') }}" type="text/javascript"></script>--}}

{{--<!-- Dashboard requires sparkline damn it  -->--}}
{{--<!-- Page js  -->--}}
{{--<script src="{{ asset('admin/minton/assets/pages/jquery.dashboard.js') }}"></script>--}}

<!-- Notification js -->
<script src="{{ asset('admin/minton/plugins/notifyjs/dist/notify.min.js') }}"></script>
<script src="{{ asset('admin/minton/plugins/notifications/notify-metro.js') }}"></script>

<script src="{{ asset('admin/assets/plugins/swal/sweetalert.min.js') }}"></script>

<!-- Modal-Effect -->
{{--<script src="{{ asset('admin/minton/plugins/custombox/dist/custombox.min.js') }}"></script>--}}
{{--<script src="{{ asset('admin/minton/plugins/custombox/dist/legacy.min.js') }}"></script>--}}
<script src="{{ asset('admin/assets/plugins/custombox-4.0.3/dist/custombox.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/custombox-4.0.3/dist/custombox.legacy.min.js') }}"></script>

{{--<script src="{{ asset('admin/assets/js/app.js') }}"></script>--}}
<script src="{{ asset('admin/assets/js/modal.js') }}"></script>

<!-- Custom main Js -->
<script src="{{ asset('admin/minton/assets/js/jquery.core.js') }}"></script>
<script src="{{ asset('admin/minton/assets/js/jquery.app.js') }}"></script>
<script>
    $("#logout-app").click(function(){
        document.getElementById('logout-form').submit();
    });
</script>
@stack('scripts')

</body>
</html>