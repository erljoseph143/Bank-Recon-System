<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/progressBar/style.css')}}">
    <!-- Bootstrap Dialog -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.css')}}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.min.css')}}">
</head>
<body>
    <!-- Process Bar when uploading -->
    <div id="progressDiv" style="position:fixed">
        <center><span style="color: #000000;font-weight: bold"><img src="{{asset('img/ajax.gif')}}" width="4%" height="4%">&nbsp;&nbsp;Uploading your file(s) may take a while. Please Wait.</span></center><br/>
        <div id="progressback">
            <div id="progress"></div>
        </div>
        <div id="percentDiv" style="width">0%</div>
        <div id="text">processing.</div>
        <!--<div id="resultsDiv">
            <div id="resultsHere"></div>
        </div>-->
    </div>
    <script src="{{asset('js/libs/jquery-2.1.1.min.js')}}"></script>
    <!-- BOOTSTRAP JS -->
    <script src="{{asset('js/bootstrap/bootstrap.min.js')}}"></script>

    <script src="{{asset('assets/bootstrap-dialog/js/bootstrap-dialog.min.js')}}"></script>

</body>
</html>

