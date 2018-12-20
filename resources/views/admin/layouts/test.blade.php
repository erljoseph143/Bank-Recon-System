<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <link rel="shortcut icon" href="{{ asset('admin/minton/assets/images/favicon_1.ico') }}">

    <title>{{ $title }}</title>


    @stack('styles')
    {{--<link href="{{ asset('admin/minton/plugins/custombox/dist/custombox.min.css') }}" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ asset('admin/assets/plugins/custombox-4.0.3/dist/custombox.min.css') }}">
    <link href="{{ asset('admin/minton/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/minton/assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/minton/assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <script src="{{ asset('admin/minton/assets/js/modernizr.min.js') }}"></script>


</head>


<body class="fixed-left-void">

<!-- Begin page -->
<div id="wrapper" class="forced enlarged">

    <!-- Top Bar Start -->
    <div class="topbar">

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="text-center">
                <a href="home" class="logo"><i class="mdi mdi-radar"></i> <span>BRS Admin</span></a>
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
                            <h5 class="text-overflow"><small>Welcome ! John</small> </h5>
                        </div>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-star-variant"></i> <span>Profile</span>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="mdi mdi-settings"></i> <span>Settings</span>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="mdi mdi-lock-open"></i> <span>Lock Screen</span>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="mdi mdi-logout"></i> <span>Logout</span>
                        </a>

                    </div>
                </li>

            </ul>

            <ul class="list-inline menu-left mb-0">
                <li class="float-left">
                    <button class="button-menu-mobile open-left waves-light waves-effect">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </li>
                <li class="hide-phone app-search">
                    <form role="search" class="">
                        <input type="text" placeholder="Search..." class="form-control">
                        <a href="#"><i class="fa fa-search"></i></a>
                    </form>
                </li>
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

                            <h4 class="page-title">{{ $pagetitle }}</h4>

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
                                <h3 class="portlet-title">Lists of {{ $doctitle }} @yield('badge')</h3>
                                <div class="portlet-widgets">

                                    @yield('top-buttons')

                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="panel-collapse collapse show">
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

<script type="text/javascript" src="{{ asset('admin/minton/plugins/multiselect/js/jquery.multi-select.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('admin/minton/plugins/jquery-quicksearch/jquery.quicksearch.js') }}"></script>--}}
<script src="{{ asset('admin/minton/plugins/select2/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin/minton/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') }}" type="text/javascript"></script>
{{--<script src="{{ asset('admin/minton/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js') }}" type="text/javascript"></script>--}}

<script src="{{ asset('admin/minton/plugins/moment/moment.js') }}"></script>
<script src="{{ asset('admin/minton/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('admin/minton/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('admin/minton/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script src="{{ asset('admin/minton/assets/pages/jquery.form-advanced.init.js') }}"></script>

<!-- Custom main Js -->
{{--<script src="{{ asset('admin/minton/assets/js/jquery.core.js') }}"></script>--}}
{{--<script src="{{ asset('admin/minton/assets/js/jquery.app.js') }}"></script>--}}

</body>
</html>