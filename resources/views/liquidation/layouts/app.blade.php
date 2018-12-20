<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.2
Version: 3.7.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>Metronic | Admin Dashboard Template</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ url('logbook/metronic/assets/global/css/fonts.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/uniform/css/uniform.default.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"/>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/admin/pages/css/tasks.css') }}"/>
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/select2/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}"/>
    <!-- END PAGE LEVEL STYLES -->

    <!-- BEGIN PLUGINS USED BY X-EDITABLE -->

    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-datepicker/css/datepicker.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css') }}"/>
    <!-- END PLUGINS USED BY X-EDITABLE -->

    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/css/components.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/global/css/plugins.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/admin/layout2/css/layout.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/admin/layout2/css/themes/default.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('logbook/metronic/assets/admin/layout2/css/custom.css') }}"/>
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-sidebar-closed-hide-logo page-container-bg-solid page-sidebar-closed-hide-logo page-header-fixed">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="index.html">
                <img src="logbook/metronic/assets/admin/layout2/img/logo.png" alt="logo" class="logo-default" style="height: 50px;width: 50px;margin-top: 5px;margin-left: 40px;"/>

            </a>
            <div class="menu-toggler sidebar-toggler">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE ACTIONS -->
        <!-- DOC: Remove "hide" class to enable the page header actions -->
        <div class="page-actions hide">
            <div class="btn-group">
                <button type="button" class="btn btn-circle red-pink dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-bar-chart"></i>&nbsp;<span class="hidden-sm hidden-xs">New&nbsp;</span>&nbsp;<i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="javascript:;">
                            <i class="icon-user"></i> New User </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="icon-present"></i> New Event <span class="badge badge-success">4</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="icon-basket"></i> New order </a>
                    </li>
                    <li class="divider">
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="icon-flag"></i> Pending Orders <span class="badge badge-danger">4</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="icon-users"></i> Pending Users <span class="badge badge-warning">12</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-circle green-haze dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-bell"></i>&nbsp;<span class="hidden-sm hidden-xs">Post&nbsp;</span>&nbsp;<i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="javascript:;">
                            <i class="icon-docs"></i> New Post </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="icon-tag"></i> New Comment </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="icon-share"></i> Share </a>
                    </li>
                    <li class="divider">
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="icon-flag"></i> Comments <span class="badge badge-success">4</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="icon-users"></i> Feedbacks <span class="badge badge-danger">2</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END PAGE ACTIONS -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <!-- BEGIN HEADER SEARCH BOX -->
            <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
            <div class="col-md-6" style="height: inherit;color:white;text-align:left;padding:24px">
                Treasury Deposit and Cash Pull Out Monitoring
            </div>

            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img alt="" class="img-circle" src="logbook/metronic/assets/admin/layout2/img/avatar3_small.jpg"/>
                            <span class="username username-hide-on-mobile">
						Nick </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">

                            <li>
                                <form method="post" id="logout-form" action="{{url('logout')}}">
                                    {{csrf_field()}}
                                </form>
                                <a href="#" class="logout">
                                    <i class="icon-key"></i> Log Out
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>

<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
            <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
            <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
            <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        @include('liquidation.layouts.liquidationSidebar')
        <!-- END SIDEBAR MENU -->
        </div>
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

            <!-- BEGIN PAGE HEADER-->

            <div class="page-bar">
                <ul class="page-breadcrumb">
                    {{--<li>--}}
                    {{--<i class="fa fa-home"></i>--}}
                    {{--<a href="#">Home</a>--}}
                    {{--<i class="fa fa-angle-right"></i>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="#">Dashboard</a>--}}
                    {{--</li>--}}
                </ul>
                <div class="page-toolbar">
                    <div id="dashboard-report-range" class="tooltips btn btn-fit-height btn-sm green-haze btn-dashboard-daterange" data-container="body" data-placement="left" data-original-title="Change dashboard date range">
                        <i class="icon-calendar"></i>
                        &nbsp;&nbsp; <i class="fa fa-angle-down"></i>
                        <!-- uncomment this to display selected daterange in the button
&nbsp; <span class="thin uppercase visible-lg-inline-block"></span>&nbsp;
<i class="fa fa-angle-down"></i>
-->
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER-->





            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <!-- BEGIN REGIONAL STATS PORTLET-->
                    <div class="portlet light data-body">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-share font-red-sunglo"></i>
                                <span class="caption-subject font-red-sunglo bold uppercase title-page">{{$content_title}}</span>
                            </div>
                            <div class="actions">
                                <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;">
                                </a>
                            </div>
                        </div>
                        <div class="log hidden"></div>
                        <div class="portlet-body" id="content">

                            @include('liquidation.addCash')

                        </div>
                    </div>
                    <!-- END REGIONAL STATS PORTLET-->
                </div>

            </div>
            <div class="clearfix">
            </div>

        </div>
    </div>
    <!-- END CONTENT -->
    <!-- BEGIN QUICK SIDEBAR -->
    <!--Cooming Soon...-->
    <!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner">
        2014 &copy; Metronic by keenthemes.
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="{{ url('logbook/metronic/assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/excanvas.min.js') }}"></script>
<![endif]-->
<script src="{{ url('logbook/metronic/assets/global/plugins/jquery.min.js') }}"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/jquery-migrate.min.js') }}"></script>


<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="{{ url('logbook/metronic/assets/global/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ url('logbook/metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
{{--<script src="{{ url('logbook/metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}"></script>--}}
{{--<script src="{{ url('logbook/metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>--}}
{{--<script src="{{ url('logbook/metronic/assets/global/plugins/jquery.blockui.min.js') }}"></script>--}}
{{--<script src="{{ url('logbook/metronic/assets/global/plugins/jquery.cokie.min.js') }}"></script>--}}
{{--<script src="{{ url('logbook/metronic/assets/global/plugins/uniform/jquery.uniform.min.js') }}"></script>--}}
<script src="{{ url('logbook/metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
{{--<script type="text/javascript" src="{{ url('logbook/metronic/assets/global/plugins/select2/select2.min.js') }}"></script>--}}
<script type="text/javascript" src="{{ url('logbook/metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
{{--<script type="text/javascript" src="{{ url('logbook/metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ url('logbook/metronic/assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ url('logbook/metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js') }}"></script>--}}
<script type="text/javascript" src="{{ url('logbook/metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="{{asset('assets/bootstrap-dialog/js/bootstrap-dialog.min.js')}}"></script>
<script src="{{ url('logbook/maskmoney/jquery.maskMoney.js') }}" type="text/javascript"></script>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ url('logbook/metronic/assets/global/scripts/metronic.js') }}"></script>
<script src="{{ url('logbook/metronic/assets/admin/layout2/scripts/layout.js') }}"></script>
<script src="{{ url('logbook/metronic/assets/admin/layout2/scripts/demo.js') }}"></script>
<script src="{{ url('logbook/metronic/assets/admin/pages/scripts/index.js') }}"></script>
<script src="{{ url('logbook/metronic/assets/admin/pages/scripts/tasks.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<!-- BEGIN PLUGINS USED BY X-EDITABLE -->
<script type="text/javascript" src="{{url('logbook/metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script type="text/javascript" src="{{url('logbook/metronic/assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js')}}"></script>
<script type="text/javascript" src="{{url('logbook/metronic/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
{{--<script type="text/javascript" src="{{url('logbook/metronic/assets/global/plugins/moment.min.js')}}"></script>--}}
{{--<script type="text/javascript" src="{{url('logbook/metronic/assets/global/plugins/jquery.mockjax.js')}}"></script>--}}
<script type="text/javascript" src="{{url('logbook/metronic/assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js')}}"></script>
<!-- END X-EDITABLE PLUGIN -->

<script>
    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
     //   Demo.init(); // init demo features
        Index.init();
//        Index.initDashboardDaterange();
//        Index.initJQVMAP(); // init index page's custom scripts
//        Index.initCalendar(); // init index page's custom scripts
//        Index.initCharts(); // init index page's custom scripts
//        Index.initChat();
//        Index.initMiniCharts();
        Tasks.initDashboardWidget();
    });

    $(".logout").click(function(e){
        e.preventDefault();
       $("#logout-form").submit();
    });
</script>
@include('liquidation.layouts.js');

@stack('scripts')

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>