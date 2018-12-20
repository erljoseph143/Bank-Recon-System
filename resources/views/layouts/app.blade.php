<!doctype html class="smart-style-6">
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

    <title> BRS </title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/font-awesome.min.css')}}">

    <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/smartadmin-production-plugins.min.css')}}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/smartadmin-production.min.css')}}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/smartadmin-skins.min.css')}}">

    <!-- SmartAdmin RTL Support  -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/smartadmin-rtl.min.css')}}">

    <!-- We recommend you use "your_style.css" to override SmartAdmin
         specific styles this will also ensure you retrain your customization with each SmartAdmin update.
    <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/jquery-ui.css')}}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/jquery-ui.structure.css')}}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/jquery-ui.theme.css')}}">


    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/demo.min.css')}}">

    <!-- Bootstrap Dialog -->
    {{--<link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.css')}}">--}}
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.min.css')}}">

    <link rel="stylesheet" href="{{asset('css/your_style.css')}}">
    <!-- FAVICONS -->
    <link rel="shortcut icon" href="{{asset('img/favicon/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('img/favicon/favicon.ico')}}" type="image/x-icon">

    <!-- GOOGLE FONT -->
    {{--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">--}}

    <!-- Specifying a Webpage Icon for Web Clip
         Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
    <link rel="apple-touch-icon" href="{{asset('img/splash/sptouch-icon-iphone.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('img/splash/touch-icon-ipad.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('img/splash/touch-icon-iphone-retina.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('img/splash/touch-icon-ipad-retina.png')}}">

    <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Startup image for web apps -->
    <link rel="apple-touch-startup-image" href="{{asset('img/splash/ipad-landscape.png')}}" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
    <link rel="apple-touch-startup-image" href="{{asset('img/splash/ipad-portrait.png')}}" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
    <link rel="apple-touch-startup-image" href="{{asset('img/splash/iphone.png"')}} media="screen and (max-device-width: 320px)">

    <style>
        .modal-content {
            border-radius:0px;
            -webkit-border-radius: 0px;
            -moz-border-radius: 0px;
            webkit-box-shadow: none;
            box-shadow:none;
        }
        .bootstrap-dialog .modal-header {
            border-top-left-radius: 0px;!important;
            border-top-right-radius: 0px;!important;
        }
        .btn {
             border-radius: 0px;
             -webkit-border-radius: 0px;
            -moz-border-radius: 0px;

        }
        .modal-footer {
            padding: 6px;

        }
    </style>

</head>
<body class="desktop-detected smart-style-6">

<!-- HEADER -->
<header id="header">
    <div id="logo-group">

                   <span style="width:730px;height: auto;padding-left: 6px;margin-top:8px;color:#FFEB3B ;">
					<img src="img/logo-o.png" width="30px" height="30px" alt="SmartAdmin"> Bank Reconciliation System : {{$com}} : {{$bu}}
                   </span>
        <!-- Note:chartreuse The activity badge color changes when clicked and resets the number to 0
        Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->



    </div>


    <!-- pulled right: nav area -->
    <div class="pull-right">

        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->



        <!-- logout button -->
        <div id="logout" class="btn-header transparent pull-right">
            {{--<a href="{{route('logout')}}" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a>--}}

            <span>
                <a href="" id="logout-app"
               onclick="event.preventDefault();"><i class="fa fa-sign-out">
                        {{--{{ $auth  }}--}}
                    </i></a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
           </span>

        </div>
        <!-- end logout button -->

        <!-- search mobile button (this is hidden till mobile view port) -->
        <div id="search-mobile" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
        </div>
        <!-- end search mobile button -->



        <!-- fullscreen button -->
        <div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
        </div>
        <!-- end fullscreen button -->





    </div>
    <!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->

<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

    <!-- User info -->
    <div class="login-info">
				<span> <!-- User image size is adjusted inside CSS, it should stay as it -->

					<a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                        @if(Auth::user()->profile_pic=="")
						    <img src="img/avatars/female.png" alt="me" class="online" id="profile-pic" />
                        @else
                            <img src="{{Auth::user()->profile_pic}}" alt="me" class="online" id="profile-pic" />
                        @endif
						<span>
							{{Auth::user()->firstname . " " . Auth::user()->lastname}}
						</span>
						<i class="fa fa-angle-down"></i>
					</a>

				</span>
    </div>
    <!-- end user info -->
    @if(strtolower($auth) == "accounting" )
        @include('layouts.accounting_nav')
    @elseif(strtolower($auth) == "rms")
        @include('layouts.rms_nav')
    @elseif(strtolower($auth) == "accounting")
        @include('layouts.accounting_nav')
    @endif

    <span class="minifyme" data-action="minifyMenu">
				<i class="fa fa-arrow-circle-left hit"></i>
			</span>

</aside>
<!-- END NAVIGATION -->


<!-- #MAIN PANEL -->
<div id="main" role="main">

    <!-- RIBBON -->
    <div id="ribbon">

				<span class="ribbon-button-alignment">
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh" rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true" data-reset-msg="Would you like to RESET all your saved widgets and clear LocalStorage?"><i class="fa fa-refresh"></i></span>
				</span>

        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <!-- This is auto generated -->
        </ol>
        <!-- end breadcrumb -->

        <!-- You can also add more buttons to the
        ribbon for further usability

        Example below:

        <span class="ribbon-button-alignment pull-right" style="margin-right:25px">
            <a href="#" id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa fa-grid"></i> Change Grid</a>
            <span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa fa-plus"></i> Add</span>
            <button id="search" class="btn btn-ribbon" data-title="search"><i class="fa fa-search"></i> <span class="hidden-mobile">Search</span></button>
        </span> -->

    </div>
    <!-- END RIBBON -->

    <!-- #MAIN CONTENT -->
    <div id="content" style="min-height:36em;">
            @yield('content')
    </div>

    <!-- END #MAIN CONTENT -->

</div>
<!-- END #MAIN PANEL -->

<!-- #PAGE FOOTER -->
<div class="page-footer">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <span class="txt-color-white">Bank Reconciliation System Copy Right © 2014-2016</span>
        </div>


    </div>
    <!-- end row -->
</div>
<!-- END FOOTER -->


    <!--================================================== -->

<script src="{{asset('js/jquery.min.js')}}"></script>
<script>
    if (!window.jQuery) {
        document.write('<script src="{{asset('js/libs/jquery-2.1.1.min.js')}}"><\/script>');
    }
</script>

<script src="{{asset('js/jquery-ui.min.js')}}"></script>
<script>
    if (!window.jQuery.ui) {
        //document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');
    }
</script>

    <!-- IMPORTANT: APP CONFIG -->
    <script src="{{asset('js/app.config.js')}}"></script>

    <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
    <script src="{{asset('js/plugin/jquery-touch/jquery.ui.touch-punch.min.js')}}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{asset('js/bootstrap/bootstrap.min.js')}}"></script>

    <script src="{{asset('assets/bootstrap-dialog/js/bootstrap-dialog.min.js')}}"></script>

    <!-- CUSTOM NOTIFICATION -->
    <script src="{{asset('js/notification/SmartNotification.min.js')}}"></script>

    <!-- JARVIS WIDGETS -->
    <script src="{{asset('js/smartwidgets/jarvis.widget.min.js')}}"></script>

    <!-- EASY PIE CHARTS -->
    <script src="{{asset('js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js')}}"></script>

    <!-- SPARKLINES -->
    <script src="{{asset('js/plugin/sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- JQUERY VALIDATE -->
    <script src="{{asset('js/plugin/jquery-validate/jquery.validate.min.js')}}"></script>

    <!-- JQUERY MASKED INPUT -->
    <script src="{{asset('js/plugin/masked-input/jquery.maskedinput.min.js')}}"></script>

    <!-- JQUERY SELECT2 INPUT -->
    <script src="{{asset('js/plugin/select2/select2.min.js')}}"></script>

    <!-- JQUERY UI + Bootstrap Slider -->
    <script src="{{asset('js/plugin/bootstrap-slider/bootstrap-slider.min.js')}}"></script>

    <!-- browser msie issue fix -->
    <script src="{{asset('js/plugin/msie-fix/jquery.mb.browser.min.js')}}"></script>

    <!-- FastClick: For mobile devices -->
    <script src="{{asset('js/plugin/fastclick/fastclick.min.js')}}"></script>

    <!--[if IE 8]>

    <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

    <![endif]-->

    <!-- Demo purpose only -->
    <script src="{{asset('js/demo.min.js')}}"></script>

    <!-- MAIN APP JS FILE -->
    <script src="{{asset('js/app.min.js')}}"></script>

    <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
    <!-- Voice command : plugin -->
    <script src="{{asset('js/speech/voicecommand.min.js')}}"></script>

    <!-- SmartChat UI : plugin -->
    <script src="{{asset('js/smart-chat-ui/smart.chat.ui.min.js')}}"></script>
    <script src="{{asset('js/smart-chat-ui/smart.chat.manager.min.js')}}"></script>

    <!-- PAGE RELATED PLUGIN(S) -->

    <!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
    <script src="{{asset('js/plugin/flot/jquery.flot.cust.min.js')}}"></script>
    <script src="{{asset('js/plugin/flot/jquery.flot.resize.min.js')}}"></script>
    <script src="{{asset('js/plugin/flot/jquery.flot.time.min.js')}}"></script>
    <script src="{{asset('js/plugin/flot/jquery.flot.tooltip.min.js')}}"></script>

    <!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->
    <script src="{{asset('js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
    <script src="{{asset('js/plugin/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>

    <!-- Full Calendar -->
    <script src="{{asset('js/plugin/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/plugin/fullcalendar/jquery.fullcalendar.min.js')}}"></script>
    <script src="{{asset('js/jquery.form.js')}}"></script>
	<script src="{{ url('logbook/maskmoney/jquery.maskMoney.js') }}" type="text/javascript"></script>
    <script >

        $("#logout-app").click(function(){
            BootstrapDialog.show({
                title:'Logout User',
                message:'Are you sure you want to logout?',
                type:BootstrapDialog.TYPE_INFO,
                size:BootstrapDialog.SIZE_WIDE,
                buttons:[{
                    label:'Yes',
                    cssClass:'btn btn-success',
                    icon:'glyphicon glyphicon-thumbs-up',
                    action:function(dialogRef)
                    {
                        document.getElementById('logout-form').submit();
                        dialogRef.close();
                    }
                },
                    {
                        label:'No',
                        cssClass:'btn btn-danger',
                        icon:'glyphicon glyphicon-thumbs-down',
                        action:function(dialogRef)
                        {
                            dialogRef.close();
                        }
                    }]

            });

            //alert("ahhahah");
        });

    /*
    *****************************************************************************
    * Load of Pages
    * ***************************************************************************
    */
$(document).ready(function () {
    $("#load-dashboard-rms").click(function (e) {
        e.preventDefault();
            $.ajax({
                type:"GET",
                url:"dashboard",
                success:function(data)
                {
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });
    });

    $("#load-dashboard-acct").click(function (e) {
        e.preventDefault();
        var me = "dfsdf";
            $.ajax({
                type:"GET",
                url:"dashboard-acct",
                success:function(data)
                {
                    $("#content").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                       BootstrapDialog.show({
                           type:BootstrapDialog.TYPE_INFO,
                           title:'Unauthorized',
                           message:'Sorry your session is expired, please login back',
                           buttons:[
                               {
                                   label:'close',
                                   icon:'glyphicon glyphicon-remove',
                                   cssClass:'btn btn-danger',
                                   action:function(dialogRef)
                                   {
                                       window.location.reload();
                                       dialogRef.close();
                                   }
                               }
                           ]
                       });
                    }
                }
            });
    });

    $("#profile-pic").click(function(){
        BootstrapDialog.show({
            title:'Profile Pic',
//            type:BootstrapDialog.TYPE_INFO,
//            size:BootstrapDialog.SIZE_WIDE,
            message:$('<div></div>').load('view-profile',function(e,st){

                if(e.status == 401)
                {
                    BootstrapDialog.show({
                        title:'Unauthorized',
                        message:'Sorry your session is expired, please login back',
                        buttons:[{
                            label:'close',
                            icon:'glyphicon glyphicon-remove',
                            cssClass:'btn btn-danger',
                            action:function(dialogRef)
                            {
                                window.location.reload();
                                dialogRef.close();
                            }
                        }]
                    })
                }
            }),

        });
    });

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
    </script>
@stack('scripts')
</body>
</html>