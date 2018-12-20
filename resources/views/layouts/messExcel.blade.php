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
            width:80%;
        }
    </style>
</head>
<body>
{{$errors,$title,$route,$getRequest}}
<script src="{{asset('js/libs/jquery-2.1.1.min.js')}}"></script>
<!-- BOOTSTRAP JS -->
<script src="{{asset('js/bootstrap/bootstrap.min.js')}}"></script>

<script src="{{asset('assets/bootstrap-dialog/js/bootstrap-dialog.min.js')}}"></script>
<script>
    {{--BootstrapDialog.show({--}}
        {{--title:'{{$title}}',--}}
        {{--message:'{!! $errors !!}',--}}
        {{--type:BootstrapDialog.{{"TYPE_DANGER"}},--}}
        {{--size:BootstrapDialog.SIZE_WIDE,--}}
        {{--closable:false,--}}
        {{--draggable:true,--}}
        {{--buttons:[{--}}
            {{--label:'Ok',--}}
            {{--icon:'glyphicon glyphicon-thumbs-up',--}}
            {{--cssClass:'btn btn-success',--}}
            {{--action:function (dialogRef) {--}}
                {{--dialogRef.close();--}}
                {{--{!! $getRequest !!}--}}
                {{--@if(strlen($route)>0)--}}

                    {{--setTimeout(function(){--}}
                    {{--window.location='{{$route}}';--}}
                {{--},300);--}}

                {{--@endif--}}
            {{--}--}}
        {{--}]--}}
    {{--});--}}

    function loadError(filename,col,row)
    {
        $.Ajax({
            type:'get',
            url:'read_bank_error/'+filename+"/"+col+"/"+row,
            success:function(data)
            {
                BootstrapDialog.show({
                    title:filename,
                    message:data,
                    buttons:[{
                        label:'close',
                        action:function (dialogRef) {
                            dialogRef.close();
                        }
                    }]
                });
            }
        });
    }
</script>
</body>
</html>