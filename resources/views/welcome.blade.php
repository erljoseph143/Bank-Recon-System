<!DOCTYPE html>
<html>

<head>
    <title>Bank Recon System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- global level css -->
    <link href="{{asset('josh/css/bootstrap.min.css')}}" rel="stylesheet" />
    <!-- end of global level css -->
    <link href="{{asset('josh/vendors/iCheck/css/square/blue.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('josh/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}" rel="stylesheet" />
    <!-- page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('josh/css/pages/login.css')}}" />
    <!-- end of page level css -->
</head>

<body style="background-image: url('backgorud.png')">
<div class="container">
    <div class="row vertical-offset-100">
        <div class="col-sm-6 col-sm-offset-3  col-md-5 col-md-offset-4 col-lg-4 col-lg-offset-4">
            <div id="container_demo">
                <a class="hiddenanchor" id="toregister"></a>
                <a class="hiddenanchor" id="tologin"></a>
                <a class="hiddenanchor" id="toforgot"></a>
                <div id="wrapper">
                    <div id="login" class="animate form">
                        <form method="POST" action="{{ route('login') }}" id="authentication" autocomplete="on" method="post">
                            {!! csrf_field() !!}
                            <h3 class="black_bg">
                                <img width="180px" height="150px" src="img/brs-logo.png" alt="josh logo">
                            </h3>
                            <div class="form-group ">
                                <label style="margin-bottom:0;" for="username" class="uname control-label"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#3c8dbc" data-hc="#3c8dbc"></i> Username
                                </label>
                                <input id="username" name="username" placeholder="Username" value="" />
                                <div class="col-sm-12">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label style="margin-bottom:0;" for="password" class="youpasswd"> <i class="livicon" data-name="key" data-size="16" data-loop="true" data-c="#3c8dbc" data-hc="#3c8dbc"></i> Password
                                </label>
                                <input type="password" id="password" name="password" placeholder="Enter a password" />
                                <div class="col-sm-12">
                                </div>
                            </div>
                            {{--<div class="form-group">--}}
                                {{--<label>--}}
                                    {{--<input type="checkbox" name="remember-me" id="remember-me" value="remember-me" class="square-blue" /> Keep me logged in--}}
                                {{--</label>--}}
                            {{--</div>--}}
                            <p class="login button">
                                <input type="submit" value="Log In" class="btn btn-success" />
                            </p>
                            <p class="change_link">
                            @if(count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)

                                            <li>{{$error}}</li>

                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </p>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- global js -->
<script src="{{asset('josh/js/app.js')}}" type="text/javascript"></script>
<!-- end of global js -->
<script src="{{asset('josh/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" type="text/javascript"></script>
<script src="{{asset('josh/vendors/iCheck/js/icheck.js')}}" type="text/javascript"></script>
<script src="{{asset('josh/js/pages/login.js')}}" type="text/javascript"></script>
</body>

</html>
