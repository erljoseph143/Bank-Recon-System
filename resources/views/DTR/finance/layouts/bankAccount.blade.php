{!! Form::select('bankAcct',[''=>'---------------------Select Bank Account-----------------']+$bankAcct,null,['class'=>'form-control','id'=>'bankAcct']) !!}

<script>
    $("#bankAcct").change(function(){
        var bank = $('#bankAcct option:selected').text();
        if(bank.match(/BPI/))
        {
            $(".bpi-type").removeClass("hidden");
        }
        else
        {
            $(".bpi-type").addClass("hidden");
        }
    });
</script>