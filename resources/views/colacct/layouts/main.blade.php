<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('colacct/assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    {{--<!-- Font Awesome -->--}}
    <link rel="stylesheet" href="{{ asset('colacct/assets/css/material-design/css/material-design-iconic-font.min.css') }}">
    {{--<!-- Ionicons -->--}}
    <link rel="stylesheet" href="{{ asset('colacct/assets/plugins/ionicons-2.0.1/css/ionicons.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('colacct/assets/plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('colacct/assets/dataTables/responsive.bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('colacct/assets/dist/css/bootstrap-dialog.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('colacct/assets/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('colacct/assets/dist/css/skins/my-skin.css') }}">
    <!-- iCheck -->
<!-- <link rel="stylesheet" href="{{ asset('colacct/assets/plugins/iCheck/flat/blue.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('colacct/assets/plugins/iCheck/all.css') }}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ asset('colacct/assets/plugins/morris/morris.css') }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ asset('colacct/assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('colacct/assets/plugins/datepicker/datepicker3.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('colacct/assets/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('colacct/assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('colacct/assets/css/plugins/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('colacct/assets/plugins/sweetalert-master/dist/sweetalert.css') }}">

    @stack('styles')

    <link rel="stylesheet" href="{{ asset('colacct/assets/css/main.css') }}">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-red sidebar-mini page-{{ $title }}" data-page-title="{{ $title }}" data-url="{{ 'url' }}">

<div class="wrapper">
    @include('colacct.layouts.header')
    @include('colacct.layouts.sidebar')
    @yield('content')
</div>
    {{--<!-- ./wrapper -->--}}
<!-- jQuery 2.2.3 -->
<script src="{{ asset('colacct/assets/plugins/jQuery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('colacct/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

<!------------------------------------------------- My customs --------------------------------------------------->
<!-- Gigamit na pero dili pa sa tanan -->
<script type="text/javascript" src="{{ asset('colacct/assets/plugins/sweetalert-master/dist/sweetalert.min.js') }}"></script>

<!-- Wa pa ni gamita -->
<script type="text/javascript" src="{{ asset('colacct/assets/js/jquery.noty.js') }}"></script>

<script type="text/javascript" src="{{ asset('colacct/assets/plugins/chartjs/Chart.min.js') }}"></script>

<?php
if (isset($with_chart)) {
if ($with_chart == true) {
?>
<script id="mychart-32423" type="text/javascript" src="{{ asset('colacct/assets/js/mychart.js') }}"></script>
<?php
}
}

?>

<script src="{{ asset('colacct/assets/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
{{--<script src="{{ asset('assets/plugins/fastclick/fastclick.js') }}"></script>--}}
<script src="{{ asset('colacct/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('colacct/assets/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('colacct/assets/dataTables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('colacct/assets/dataTables/responsive.bootstrap.min.js') }}"></script>

<script src="{{ asset('colacct/assets/dist/js/bootstrap-dialog.min.js') }}"></script>

<!-- ayha ra ni i load ug gamiton -->
<script src="{{ asset('colacct/assets/plugins/iCheck/icheck.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('colacct/assets/dist/js/app.min.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{--<script src="{{ asset('assets/dist/js/pages/dashboard.js') }}"></script>--}}
<!-- AdminLTE for demo purposes -->
<script type="text/javascript" src="{{ asset('colacct/assets/js/json/json2.js') }}"></script>
<script src="{{ asset('colacct/assets/dist/js/demo.js') }}"></script>
<script type="text/javascript" src="{{ asset('colacct/assets/js/app.js') }}"></script>
<script id="nav-23423423" type="text/javascript" src="{{ asset('colacct/assets/js/nav.js') }}"></script>

@stack('scripts')

<script type="text/javascript" src="{{ asset('colacct/assets/js/check.js') }}"></script>

<script>
    $("#logout-app").click(function(){
        document.getElementById('logout-form').submit();
    });
</script>
</body>
</html>