<form class="form-horizontal" id="payment-form" role="form" method="post" action="">
    {{csrf_field()}}
    <table class="table table-striped table-bordered table-hover dataTable no-footer" >
        <tbody>
            <tr>
                <td class="td-label">Requested By :</td>
                <td>
                    <input type="hidden" name="cpo_id" value="{{$cpo->id}}">
                    <input type="text" name="name" class="form-control " placeholder="TCPOF #" value="{{$cpo->user->firstname ." ". $cpo->user->lastname}}" readonly required>
                </td>
                <td class="td-label">TCPOF # :</td>
                <td>
                    <input type="text" name="tcpof_no" class="form-control " placeholder="TCPOF #" value="0000108" readonly required>
                </td>
            </tr>
            <tr>
                <td class="td-label">Department / Section :</td>
                <td>
                    <input type="text" name="dep_sec_name" class="form-control " placeholder="Department" value="{{$cpo->department->dep_name}}" readonly required>
                </td>
                <td class="td-label">Date Requested :</td>
                <td>
                    <input type="text" name="cpo_date" class="form-control " placeholder="Default Input" value="{{date('m/d/Y',strtotime($cpo->pull_out_date))}}" readonly required>
                </td>
            </tr>
            <tr>
                <td class="td-label">Requested Amount :</td>
                <td>

                    <input type="text" readonly name="cpo_amt" class="form-control  amount" style="text-align:right" placeholder="" value="₱ {{number_format($cpo->amount_edited,2)}}" required>
                </td>
                <td class="td-label">Amount in Words :</td>
                <td>
                    <input type="text" name="amount_words" class="form-control " value="{{$cpo->amt_words}}" readonly required>
                </td>
            </tr>
            <tr>
                <td class="td-label">Check Number :</td>
                <td>
                    <input type="text" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  name="checkno" class="form-control  checkno" style="" placeholder="Enter Check No here." required>
                </td>
                <td class="td-label">Check Amount :</td>
                <td>
                    <input type="text" name="check_amt" class="form-control  check_amt" style="text-align: right;" placeholder="Enter Check Amount here." required>
                </td>
            </tr>

        </tbody>
    </table>
    <div class="alert alert-danger hidden" id="error-msg"></div>
    <div class="form-actions right1">

        <button type="button" class="btn default cancel">Cancel</button>
        <button type="submit" class="btn green">Submit</button>
    </div>
</form>

<script>
    $(".modal-dialog").addClass("modal-full");

    $(document).on('focus','.check_amt',function(){
        $(this).maskMoney();
    });

    $(".cancel").click(function(){
        mbox.close();
    });

    $("#payment-form").submit(function(e){
        e.preventDefault();

        var checkno  = $(".checkno").val();
        var checkAmt = $(".check_amt").val();
        var amountReq = $(".amount").val().replace('₱','').trim();
        //alert(parseFloat($(".amount").val().replace(/\,/g,'')));
        if(checkno=='' && checkAmt=='')
        {
            $("#error-msg").text('Please input Check No and Check Amount');
            $("#error-msg").removeClass("hidden");
        }
        else if(checkno=='')
        {
            $("#error-msg").text('Please input Check No');
            $("#error-msg").removeClass("hidden");
        }
        else if(checkAmt=='')
        {
            $("#error-msg").text('Please input Check Amount');
            $("#error-msg").removeClass("hidden");
        }
        else if(parseFloat(checkAmt.replace(/\,/g,'')) > parseFloat(amountReq.replace(/\,/g,'')))
        {
            $("#error-msg").text('Opps! The Check Amount is greater than the amount to be paid');
            $("#error-msg").removeClass("hidden");
        }
        else
        {
            BootstrapDialog.show({
                title:'Payement',
                message:'Are you sure?',
                size:BootstrapDialog.SIZE_SMALL,
                closable:false,
                buttons:[
                    {
                        label:'Yes',
                        icon:'glyphicon glyphicon-thumbs-up',
                        cssClass:'btn btn-success',
                        action:function(dialog)
                        {
                            $("#error-msg").addClass("hidden");
                            //$("#payment-form").unbind().submit();
                            $.ajax({
                            type:'post',
                            data:$("#payment-form").serialize(),
                            url:'{{url('cashpullout/paymentsave')}}',
                            success:function(data)
                            {
                                if(data.trim()!="<p style='color:red'>The total amount you tender is greeter than amount to be paid, please view your ledger</p>")
                                {
                                    dialog.close();
                                    location.reload();
                                }
                                else
                                {
                                    console.log(data);
                                    BootstrapDialog.show({
                                        title:'Payment',
                                        message:data,

                                    });
                                }
                            }
                        })
                            dialog.close();
                        }
                    },
                    {
                        label:'No',
                        icon:'glyphicon glyphicon-thumbs-down',
                        cssClass:'btn btn-danger',
                        action:function(dialog)
                        {
                            dialog.close();
                        }
                    }
                ]
            });
        }


    });
</script>
<style>
    .td-label
    {
        color:green;
        background: #faebcc;
    }
</style>