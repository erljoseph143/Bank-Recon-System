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
    <!-- Bootstrap Dialog -->
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.css')}}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.min.css')}}">
    <style>
        .modal-lg
        {
            width:50%;
        }
    </style>
</head>
<body>
    <div class="hidden error-data">
        <table class="table table-striped table-bordered">
            <tr>
                <td colspan="2" style="color:white;text-align:center;background-color:#d9534f">
                    Error in Balancing Previous Month Ending Balance Not Equal to File Beginning Balance
                </td>
            </tr>
            <tr>
                <td>Previous Month Ending Balance as of {{$prevDate}}</td>
                <td>{{$endBal}}</td>
            </tr>
            <tr>
                <td>File Beginning Balance as of {{$bsDate}}</td>
                <td>{{$begBal}}</td>
            </tr>
        </table>
    </div>


{{--{{$errors,$title,$route,$getRequest}}--}}
<script src="{{asset('js/libs/jquery-2.1.1.min.js')}}"></script>
<!-- BOOTSTRAP JS -->
<script src="{{asset('js/bootstrap/bootstrap.min.js')}}"></script>

<script src="{{asset('assets/bootstrap-dialog/js/bootstrap-dialog.min.js')}}"></script>
<script>
    setTimeout(function(){
        var message = $(".error-data").html();
        BootstrapDialog.show({
            title:'Error',
            message:$('<div></div>').html(message),
            type:BootstrapDialog.TYPE_DANGER,
            size:BootstrapDialog.SIZE_WIDE,
            closable:false,
            draggable:true,
            buttons:[{
                label:'Ok',
                icon:'glyphicon glyphicon-thumbs-up',
                cssClass:'btn btn-success',
                action:function (dialogRef) {

                    {{--{!! $getRequest !!}--}}
                    {{--@if(strlen($route)>0)--}}

                    setTimeout(function(){
                        window.location='home';
                    },300);
                    dialogRef.close();
                    {{--@endif--}}
                }
            }]
        });
    },1000);



</script>
</body>
</html>