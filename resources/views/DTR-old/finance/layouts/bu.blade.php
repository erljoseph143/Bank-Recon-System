{!! Form::select('bu',[''=>'----------------Select Business Unit-------------']+$bu,null,['class'=>'form-control','id'=>'bu']) !!}

<script>
    var bacct = $('.b-acct').html();
    var com   = $("#com").val();
    var bu;
    $("#bu").change(function(){
        bu = $(this).val();
           if(bu!='')
           {
               $.ajax({
                       type:'get',
                       url:'{{url('dtr/bankAcct')}}/'+com+'/'+bu,
                       success:function(data)
                           {
                               $(".b-acct").html(data);
                           }
                   });
           }
           else
           {
               $(".b-acct").html(bacct);
           }
    });
</script>
