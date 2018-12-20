



{!! Form::select('bu_unit',[''=>'------------------------Select Business Unit---------------------'] +  $bu,null,['class'=>'form-control','id'=>'bu']) !!}
<script type="text/javascript">
    var buid = $("#unitid").val();
    if(buid!="")
    {
        $("#bu").val(buid).trigger("change");
    }
    else
    {

    }

    $("#bu").change(function(){
        var bu = $("#bu").val();
        if(bu!="")
        {

            $("#dis_bank_act").remove();
            $.ajax({
                type:'GET',
                url:"bankact/"+com+"/"+bu,
                success:function(data)
                {


                    $("#bank_act_sel").html(data);
                },
                error:function(e) {
                    if (e.status == 401) {
                        BootstrapDialog.show({
                            type:BootstrapDialog.TYPE_INFO,
                            title:'Unauthorized',
                            message:'Sorry your session is expired, please login back',
                            buttons:[
                                {
                                    label:'close',
                                    icon:'glyphicon glyphicon-remove',
                                    cssClass:'btn btn-danger',
                                    action:function(dialogRef)
                                    {
                                        window.location.reload();
                                        dialogRef.close();
                                    }
                                }
                            ]
                        });
                    }
                }
            });

        }
        else
        {
            $("#bank_act_sel").html('<input type="text" id="dis_bank_act" disabled placeholder="Bank Account List" class="form-control">');
        }
    });

</script>