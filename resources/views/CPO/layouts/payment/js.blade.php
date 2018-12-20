<script>

    function cpoList()
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('cashpullout/cpoList')}}',
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



</script>