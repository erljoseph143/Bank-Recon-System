
{{--<!doctype html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
    {{--<meta charset="UTF-8">--}}
    {{--<meta name="viewport"--}}
          {{--content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">--}}
    {{--<meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
    {{--<title>Document</title>--}}
    {{--<!-- Basic Styles -->--}}
    {{--<link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/bootstrap.min.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/font-awesome.min.css')}}">--}}
    {{--<!-- Bootstrap Dialog -->--}}
    {{--<link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/bootstrap-dialog/css/bootstrap-dialog.min.css')}}">--}}
    {{--<style>--}}
        {{--.modal-lg--}}
        {{--{--}}
            {{--//width:50%;--}}
        {{--}--}}
    {{--</style>--}}
{{--</head>--}}
{{--<body>--}}
{{--{{$errors,$title,$route,$getRequest}}--}}
{{--<script src="{{asset('js/libs/jquery-2.1.1.min.js')}}"></script>--}}
{{--<!-- BOOTSTRAP JS -->--}}
{{--<script src="{{asset('js/bootstrap/bootstrap.min.js')}}"></script>--}}

{{--<script src="{{asset('assets/bootstrap-dialog/js/bootstrap-dialog.min.js')}}"></script>--}}
{{--<div class="hidden dtr-error">--}}
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Filename</th>
            <th>Error Description</th>
            <th>Column</th>
            <th>Row</th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
        @foreach($errorArray as $key => $error)
            @if(count($error)>0)
                <tr>
                    <td>
                        <a href="#" onclick="loadError('{{$error[0]."|".$error[2]."|".$error[3]}}')">
                            {{$error[0]}}
                        </a>
                    </td>
                    <td>{{$error[1]}}</td>
                    <td>{{$error[2]}}</td>
                    <td>{{$error[3]}}</td>
                    <td>{{$error[4]}}</td>
                </tr>
            @endif

        @endforeach
        </tbody>
    </table>
{{--</div>--}}


<script>
    {{--BootstrapDialog.show({--}}
        {{--title:'Error in Uploading',--}}
        {{--message:$('<div></div>').html($('.dtr-error').html()),--}}
        {{--type:BootstrapDialog.TYPE_DANGER,--}}
        {{--size:BootstrapDialog.SIZE_WIDE,--}}
        {{--closable:false,--}}
        {{--draggable:true,--}}
        {{--buttons:[{--}}
            {{--label:'Ok',--}}
            {{--icon:'glyphicon glyphicon-thumbs-up',--}}
            {{--cssClass:'btn btn-success',--}}
            {{--action:function (dialogRef) {--}}

                {{--{!! $getRequest !!}--}}
                {{--@if(strlen($route)>0)--}}

                {{--setTimeout(function(){--}}
                    {{--window.location='{{url('home')}}';--}}
                {{--},300);--}}
                {{--dialogRef.close();--}}
                {{--@endif--}}
            {{--}--}}
        {{--}]--}}
    {{--});--}}

    function loadError(arrFile)
    {
        //alert("test");
        arrFile1 = arrFile.split("|");
        filename = arrFile1[0];
        col =arrFile1[1];
        row = arrFile1[2];
        $.ajax({
            type:'get',
            url:'{{url('read_bank_error')}}/'+filename+"/Error/"+row,
            success:function(data)
            {
                BootstrapDialog.show({
                    title:filename,
                    message:data,
                    type:BootstrapDialog.TYPE_SUCCESS,
                    size:BootstrapDialog.SIZE_WIDE,
                    buttons:[{
                        label:'close',
                        cssClass:'btn btn-danger',
                        icon:'glyphicon glyphicon-remove',
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