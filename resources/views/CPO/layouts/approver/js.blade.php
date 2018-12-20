<script>

    function requestedCash()
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('cashpullout/requestedCash')}}',
            success:function(data)
            {
                $(".page-breadcrumb").html('');
                $(".page-breadcrumb").html('<li>'+
                    '<i class="fa fa-home"></i>'+
                    '<a href="#" class="monthly-for-monthly">Home</a>'+
                    '<i class="fa fa-angle-right"></i>'+
                    '</li>');

                $("#content").html(data);
            }
        })
    }

    function approveRequestedCash()
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('cashpullout/approveRequestedCash')}}',
            success:function(data)
            {
                $(".page-breadcrumb").html('');
                $(".page-breadcrumb").html('<li>'+
                    '<i class="fa fa-home"></i>'+
                    '<a href="#" class="monthly-for-monthly">Home</a>'+
                    '<i class="fa fa-angle-right"></i>'+
                    '</li>');

                $("#content").html(data);
            }
        })
    }

    function cpoReplenished()
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('cashpullout/cpoReplenished')}}',
            success:function(data)
            {
                $(".page-breadcrumb").html('');
                $(".page-breadcrumb").html('<li>'+
                    '<i class="fa fa-home"></i>'+
                    '<a href="#" class="monthly-for-monthly">Home</a>'+
                    '<i class="fa fa-angle-right"></i>'+
                    '</li>');

                $("#content").html(data);
            }
        })
    }

    $('.amount').maskMoney();
    $('.amount').keyup(function(){
        $(".amt-words").val($(this).AmountInWords());
    });

</script>