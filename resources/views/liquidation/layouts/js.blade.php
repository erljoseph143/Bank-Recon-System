<script type="text/babel" src="/js/babel.min.js"></script>
<script>
//    $(document).ajaxStart(function(){
//            $("button").prop('disabled',true);
//            $("a").prop('disabled',true);
//            $('.log').text("start")
//            console.log('start');
//
//    });
//    $( document ).ajaxStop(function() {
//
//            $("button").prop('disabled',false);
//            $("a").prop('disabled',false);
//            $(".log").text("stop");
//         console.log('stop');
//    });


    function addCash()
    {
        $("#content").html('');
        $(".data-body").fadeOut("slow");
        $(".data-body").fadeIn("slow",function(){

                $.ajax({
                    type:'get',
                    url:'{{url('liquidation/addCash')}}',
//                    cache:false,
//                    processData:false,
                    success:function(data)
                    {
                        setTimeout(function(){
                            $("#content").html(data);
                        },2000);
                        return false;
                    }
                });

        });
    }

    function monthlyCash()
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $(".page-breadcrumb").html('');

                $.ajax({
                    type:'get',
                    url:'{{url('liquidation/monthlyCash')}}',
//                    cache:false,
//                    processData:false,
                    success:function(data)
                    {
                       // setTimeout(function(){
                           $("#content").html(data);
                       // },2000);
                        //return false;
                    }
                });

    }

    function dailyCash(date)
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $(".page-breadcrumb").html('');

                $.ajax({
                    type:'get',
                    url:'{{url('liquidation/dailyCash')}}/'+date,
//                    cache:false,
//                    processData:false,
                    success:function(data)
                    {
                        setTimeout(function(){
                            $(".page-breadcrumb").html('<li>'+
                                                             '<i class="fa fa-home"></i>'+
                                                                '<a href="#" class="monthly-list-cash" data-date="'+date+'">Monthly</a>'+
                                                             '<i class="fa fa-angle-right"></i>'+
                                                          '</li>');

                            $("#content").html(data);
                        },2000);
                        return false;
                    }
                });

    }

    function viewCash(date)
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $(".page-breadcrumb").html('');

                $.ajax({
                    type:'get',
                    url:'{{url('liquidation/viewCash')}}/'+date,
//                    cache:false,
//                    processData:false,
                    success:function(data)
                    {
                        setTimeout(function(){
                            $(".page-breadcrumb").html('<li>'+
                                                             '<i class="fa fa-home"></i>'+
                                                                '<a href="#" class="monthly-list-cash">Monthly</a>'+
                                                                    '<i class="fa fa-angle-right"></i>'+
                                                                '<a href="#" class="daily-cash-list" data-date="'+date+'">Daily</a>'+
                                                          '</li>');

                            $("#content").html(data);

                        },2000);
                        return false;
                    }
                });

    }

    function saveCash(url)
    {

            setTimeout(function(){

                var load = "";
                var load = $("#testing-data").html();
                $(".bootstrap-dialog-message").html('');

                BootstrapDialog.show({
                    title:'Are You sure of the Data below?',
                    message:$('<div style="overflow-y:auto; height:300px" ></div>').html(load),
                    size:BootstrapDialog.SIZE_WIDE,
                    closable:false,
                    buttons:[
                        {
                            label:' Yes',
                            icon:'glyphicon glyphicon-thumbs-up',
                            cssClass:'btn btn-sm btn-success',
                            action:function(dialog)
                            {


                                $.ajax({
                                    type:'post',
                                    url:url,
                                    data:$('#save-cash').serialize(),
                                    success:function(data)
                                    {
                                        if(data.trim()!='')
                                        {
                                            BootstrapDialog.alert(data);
                                        }
                                        else
                                        {
                                            $('#content').fadeOut('slow',function(){
                                                $.ajax({
                                                    type:'get',
                                                    url: '{{url("viewCash/".date('Y-m-d'))}}',
                                                    success:function(data)
                                                    {
                                                        $("#content").html(data);
                                                        $('#content').fadeIn('slow');
                                                    }
                                                });
                                            });
                                        }

                                    }
                                });
                                $('#save-cash')[0].reset();
                                dialog.close();
                            }
                        },
                        {
                            label:' No',
                            icon:'glyphicon glyphicon-thumbs-down',
                            cssClass:'btn btn-sm btn-danger',
                            action:function(dialog)
                            {
                                dialog.close();
                            }
                        }
                    ]
                });
            },1000);



    }
</script>