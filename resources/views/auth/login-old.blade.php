<!DOCTYPE html>
<html class="">


<head>
    <meta charset="UTF-8">
    <title>AdminLTE | Log in</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- bootstrap 3.0.2 -->
    <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{asset('css/AdminLTE.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.min.css')}}" rel="stylesheet" type="text/css" />

</head>
<body>
</br>
</br>
<!--<center><img src="img/login.png" style="align:center;"></center>-->
<div class="form-box" id="login-box" style="margin-top: -30px;border-style:none;border-radius:0px;margin-top:8%;margin-bottom:10%">

    {{--<form action="" method="post">--}}
    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
        {!! csrf_field() !!}
        <div class="body" style="box-shadow:inset 1px 1px 30px  rgba(255,255,254,0.5)">
            <div class="header" style="background-color:rgba(255,255,255,0.2);border-style:none;border-radius:0px;box-shadow:inset 1px 1px 30px  rgba(255,255,254,0.5)">
			<span style="color:#6D4100">
				HOMS - LOGIN FORM
			</span>
            </div>

            <hr style="border:1px dashed white;width:90%;font-size:20px;">

            <div class="header" style="
background-color:#E1DCD6;
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e8c069', endColorstr='#c96d23',GradientType=0 );
border-radius:0px;">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username"/>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password"/>
                </div>
                <!--<div class="form-group">
                <input type="checkbox" name="remember_me"/> Remember me
                </div>-->

            </div>
            <div class="footer" style="border-style:none;border-radius:0px;">
                <button type="submit" name="submit" id="log_me" class="btn color-button btn-block " style="border-style:none;border-radius:0px;">Sign me in</button>
            </div>
        </div>

    </form>

    <div class="margin text-center" style="border-style:none;border-radius:0px;">
        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)

                        <li>{{$error}}</li>

                    @endforeach
                </ul>
            </div>
            <br/>
    </div>
    @endif
</div>

<!-- jQuery 2.0.2 -->

<!-- Bootstrap -->
<script src="{{asset('assets/jquery/jquery-1.10.2.min.js')}}"></script>
<!-- jQuery UI 1.10.3 -->
<script src="{{asset('js/jquery-ui-1.10.3.min.js')}}" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="{{asset('js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/AdminLTE/app.js')}}" type="text/javascript"></script>

<link href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />

<script src="{{asset('assets/prettify/run_prettify.js')}}"></script>

<script src="{{asset('assets/bootstrap-dialog/js/bootstrap-dialog.min.js')}}"></script>
</body>