<script>

    function cashForDep()
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
            $.ajax({
                type:'get',
                url:'{{url('treasury/forDeposit')}}',
                success:function(data)
                {
                    $(".page-breadcrumb").html('');
                    $("#content").html(data);
                }
            });
    }

    function dailyDep(date)
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('treasury/dailyDep')}}/'+date,
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

    function viewDep(date)
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('treasury/viewDep')}}/'+date,
            success:function(data)
            {
                $(".page-breadcrumb").html('');
                $(".page-breadcrumb").html('<li>'+
                    '<i class="fa fa-home"></i>'+
                    '<a href="#" class="monthly-for-monthly">Home</a>'+
                    '<i class="fa fa-angle-right"></i>'+
                    '</li>'+
                    '<li class="monthly" data-date="'+date+'">'+
                    '<a href="#">Daily List</a>'+
                    '</li>');

                    $('#content').html(data);

            }
        })
    }

    function cashRelease()
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
            $.ajax({
                type:'get',
                url:'{{url('treasury/cashRelease')}}',
                success:function(data)
                {
                    $("#content").html(data);
                }
            });

    }

    function cashDeposited()
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
            $.ajax({
                type:'get',
                url:'{{url('treasury/monDeposited')}}',
                success:function(data)
                {
                    $(".page-breadcrumb").html('');
                    $("#content").html(data);
                }
            });

    }

    function dailyDeposited(date)
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('treasury/dailyDeposited')}}/'+date,
            success:function(data)
            {
                $(".page-breadcrumb").html('');
                $(".page-breadcrumb").html('<li>'+
                                                     '<i class="fa fa-home"></i>'+
                                                        '<a href="#" class="monthly-deposited">Home</a>'+
                                                     '<i class="fa fa-angle-right"></i>'+
                                                  '</li>');
                    $("#content").html(data);


            }
        });
    }

    function depositedCash(date)
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('treasury/deposited')}}/'+date,
            success:function(data)
            {
                $(".page-breadcrumb").html('');
                $(".page-breadcrumb").html('<li>'+
                                                '<i class="fa fa-home"></i>'+
                                                    '<a href="#" class="monthly-deposited">Home</a>'+
                                                '<i class="fa fa-angle-right"></i>'+
                                            '</li>'+
                                            '<li class="daily-dep-list" data-date="'+date+'">'+
                                                '<a href="#">Daily List</a>'+
                                            '</li>');

                    $("#content").html(data);

            }
        })
    }

</script>