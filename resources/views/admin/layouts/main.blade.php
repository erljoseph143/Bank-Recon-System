<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('admin/minton/assets/images/favicon_1.ico') }}">
    <title>{{ $title }}</title>
    <style type="text/css">
        .spinner-wrapper {
            position: fixed;
            height: 100%;
            width: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        .lds-ripple {
            display: inline-block;
            position: relative;
            width: 64px;
            height: 64px;
            top: 50%;
            left: 50%;
        }
        .lds-ripple div {
            position: absolute;
            border: 4px solid #fff;
            opacity: 1;
            border-radius: 50%;
            animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }
        .lds-ripple div:nth-child(2) {
            animation-delay: -0.5s;
        }
        @keyframes lds-ripple {
            0% {
                top: 28px;
                left: 28px;
                width: 0;
                height: 0;
                opacity: 1;
            }
            100% {
                top: -1px;
                left: -1px;
                width: 58px;
                height: 58px;
                opacity: 0;
            }
        }
    </style>
    <link href="{{ asset('admin/minton/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/minton/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    @stack('styles')
    <link href="{{ asset('admin/minton/assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/minton/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/main.css') }}" rel="stylesheet">
    <script src="{{ asset('admin/minton/assets/js/modernizr.min.js') }}"></script>

</head>

<body class="fixed-left">
<div class="spinner-wrapper">
        <div class="lds-ripple"><div></div><div></div></div>
</div>
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
            </ul>
        </nav>

    </div>
    <!-- Top Bar End -->
    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">
        <div class="sidebar-inner slimscrollleft">
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
    <!-- Start right Content here -->
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
                @yield('content')
            </div>
            <!-- end container -->
        </div>
        <!-- end content -->
        <footer class="footer">
            2016 - {{ date('Y') }} © AGC Programmers <span class="hide-phone">- Anonymous.ph</span>
        </footer>
    </div>
    <!-- End Right content here -->
</div>
<!-- END wrapper -->

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
{{--<script src="{{ asset('admin/minton/assets/js/jquery.blockUI.js') }}"></script>--}}
{{--<script src="{{ asset('admin/minton/assets/js/waves.js') }}"></script>--}}
<script src="{{ asset('admin/minton/assets/js/wow.min.js') }}"></script>
{{--<script src="{{ asset('admin/minton/assets/js/jquery.nicescroll.js') }}"></script>--}}
{{--<script src="{{ asset('admin/minton/assets/js/jquery.scrollTo.min.js') }}"></script>--}}
{{--<script src="{{ asset('admin/minton/plugins/switchery/switchery.min.js') }}"></script>--}}
<!-- Notification js -->
<script src="{{ asset('admin/minton/plugins/notifyjs/dist/notify.min.js') }}"></script>
<script src="{{ asset('admin/minton/plugins/notifications/notify-metro.js') }}"></script>
<script>
    window.onload = function () {
        $('.spinner-wrapper').fadeOut();
    }
</script>
@stack('scripts')
<!-- Custom main Js -->
<script src="{{ asset('admin/minton/assets/js/jquery.core.js') }}"></script>
<script src="{{ asset('admin/minton/assets/js/jquery.app.js') }}"></script>
<script>
    $("#logout-app").click(function(){
        document.getElementById('logout-form').submit();
    });

    {{--var source = new EventSource("{{ route('adminssecounts') }}");--}}
    {{--source.addEventListener('message', function(e) {--}}
        {{--var jsondata = JSON.parse(e.data),--}}
            {{--disburse_count = $('#disburse-counter').data('value');--}}
        {{--console.log(jsondata);--}}
        {{--console.log(disburse_count);--}}
        {{--if (disburse_count != jsondata.disburse) {--}}
            {{--$.Notification.notify('info','bottom right','Server Updates', 'A user is altering disbursement table data new table count = '+jsondata.disburse);--}}
            {{--$('#disburse-counter').text(jsondata.disburse).data('value',jsondata.disburse);--}}
        {{--}--}}
    {{--}, false);--}}

    {{--source.addEventListener('error', function(e) {--}}
        {{--if (e.readyState == EventSource.CLOSED) {--}}
            {{--// Connection was closed.--}}
            {{--console.log('Event was closed');--}}
        {{--}--}}
    {{--}, false);--}}

</script>

</body>
</html>