<form class="form-horizontal" id="CPO-form" role="form" method="post">
    {{csrf_field()}}
    <div class="form-body">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-3 control-label">Name</label>
                <div class="col-md-9">
                    <input type="hidden" name="req_by" value="{{$ses->user_id}}">
                    <input type="text" class="form-control input-lg" placeholder="Name" value="{{$ses->firstname. ' ' . $ses->lastname}}" readonly required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-3 control-label">TCPOF #:</label>
                <div class="col-md-9">
                    <input type="text" name="tcpof" class="form-control input-lg" placeholder="TCPOF #" value="{{$tcpof_no}}" readonly required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-3 control-label">Department / Section:</label>
                <div class="col-md-9">
                    <input type="hidden" name="dep_sec" value="{{$ses->dept_id}}">
                    <input type="text" name="dep_sec_name" class="form-control input-lg" placeholder="Department" value="{{$ses->department->dep_name}}" readonly required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-3 control-label">DATE: </label>
                <div class="col-md-9">
                    <input type="text" name="req_date" class="form-control input-lg" placeholder="Default Input" value="{{date('m/d/Y')}}" readonly required>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-3 control-label">Request Amount : </label>
                <div class="col-md-3">
                    <div class="input-group">
                                     <span class="input-group-addon">
                                         ₱
                                     </span>
                        <input type="text" name="amount" class="form-control input-lg amount" style="text-align:right" placeholder="" required>
                    </div>
                    <textarea name="amt_words" class="form-control amt-words" readonly rows="3" style="resize:none;margin: 0px -2px 0px 0px; height: 73px; width: 503px;" required>

                                </textarea>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-3 control-label">Purpose: </label>
                <div class="col-md-9">
                    {!! Form::select('purpose',[''=>'-------------------------Select Purpose-------------------------'] + $purpose,null,['class'=>'form-control input-lg','required' => 'required']) !!}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>



    </div>
    <div class="form-actions right1">
        <button type="reset" class="btn default cancel">Cancel</button>
        <button type="submit" class="btn green">Submit</button>
    </div>
</form>

@if(!isset($home))
<script>
    $('.amount').maskMoney();
    $('.amount').keyup(function(){
        $(".amt-words").val($(this).AmountInWords());
    });

    $("#CPO-form").submit(function(e){
        e.preventDefault();
        var form  = $(this);
        BootstrapDialog.show({
            title:'Cash Pull Out',
            message:'Are you sure you want to save?',
            size:BootstrapDialog.SIZE_SMALL,
            buttons:[
                {
                    label:'Yes',
                    icon:'glyphicon glyphicon-thumbs-up',
                    cssClass:'btn btn-sm btn-success',
                    action:function(dialog)
                    {
                        $.ajax({
                            type:'post',
                            data:form.serialize(),
                            url:'{{url('cashpullout/saveCashPullOut')}}',
                            success:function(data)
                            {
                                form[0].reset();
                            }
                        });
                        dialog.close();
                    }
                },
                {
                    label:'No',
                    icon:'glyphicon glyphicon-thumbs-down',
                    cssClass:'btn btn-sm btn-danger',
                    action:function(dialog)
                    {
                        dialog.close();
                    }
                }
            ]
        });

    });

    $(".cancel").click(function(){
        $("#CPO-form")[0].reset();
    });
</script>
@endif
<script>
    document.addEventListener('DOMContentLoaded',function(){
        $("#CPO-form").submit(function(e){
            e.preventDefault();
            var form  = $(this);
            BootstrapDialog.show({
                title:'Cash Pull Out',
                message:'Are you sure you want to save?',
                size:BootstrapDialog.SIZE_SMALL,
                buttons:[
                    {
                        label:'Yes',
                        icon:'glyphicon glyphicon-thumbs-up',
                        cssClass:'btn btn-sm btn-success',
                        action:function(dialog)
                        {
                            $.ajax({
                                type:'post',
                                data:form.serialize(),
                                url:'{{url('cashpullout/saveCashPullOut')}}',
                                success:function(data)
                                {
                                    form[0].reset();
                                }
                            });
                            dialog.close();
                        }
                    },
                    {
                        label:'No',
                        icon:'glyphicon glyphicon-thumbs-down',
                        cssClass:'btn btn-sm btn-danger',
                        action:function(dialog)
                        {
                            dialog.close();
                        }
                    }
                ]
            });

        });

        $(".cancel").click(function(){
            $("#CPO-form")[0].reset();
        });
    })
</script>