<script>
    function dashBoard()
    {

    }

    function uploadDTR()
    {
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $(".page-breadcrumb").html('');
        $.ajax({
            type:'get',
            url:'{{url('dtr/uploadDTR')}}',
            success:function(data)
            {
                setTimeout(function(){
                    $("#content").html(data);
                },2000);
                return false;
            }
        });
    }
</script>