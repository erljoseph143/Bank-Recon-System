

<div class="tabbable apbank">


    <div class="tab-content bankborder">
        <div class="tab-pane active" id="tab112">
            <div class="span6" style="padding:20px;">


                <span style="font-size: 25px;">&nbsp;Uploading Bank Statement</span>

                <!--	<span style="text-align: justify"></br>Note:<i>

                        Upload your .xls file(s) here. If you accidentally uploaded first the
                        Bank Statement without uploading the Check Voucher of Navision, please contact the admin about this.
                    </i></span>-->

                {!! Form::open(['method'=>'POST','action'=>'BankStatementController@store','files'=>true,'name'=>'actionsend']) !!}
                <div class="input-group col-md-12" style='margin-bottom:4%;'>
                    {!! Form::select('year',[''=>'--------------- Select Year --------------']+ $yearOf,null,['class'=>'form-control','id'=>'year']) !!}
                </div>
                <div class="input-group col-md-12" style='margin-bottom:4%;'>
                    {!! Form::select('company',[''=>'--------------- Select Company --------------'] + $com ,null,['class'=>'form-control','id'=>'com']) !!}
                </div>

                <div class="input-group col-md-12" id="bu_sel" style='margin-bottom:4%;'>

                    <input type="text" id="dis_bu" disabled placeholder="Business Unit" class="form-control">

                </div>

                <div class="input-group col-md-12" id="bank_act_sel" style='margin-bottom:4%;'>

                    <input type="text" id="dis_bank_act" disabled placeholder="Bank Account List" class="form-control">

                </div>

                <div class="input-prepend input-append">

                    <span class="add-on" style="font-size:16px;">&nbsp; .xls, .xlsx File(s)</span>
                    <input class="form-control" style="display: none" type="file" multiple="multiple" name="mainfiles[]" id="mainfiles2s" required/>
                    {{--{{Form::file('mainfiles[]',null,['class'=>'form-control hidden','id'=>'mainfiles2s','multiple'=>'multiple'])}}--}}
                    <input type="text" id="mainfile1s" class="input form-control" multiple="multiple" required for="mainfiles2s" placeholder="No file(s) chosen" style="display:inline;width: 53%;border:1px solid grey;background-color: #FFF" disabled autocomplete="off"/>
                    <label for="mainfiles2s" data-toggle="tooltip" title="CLICK HERE TO BROWSE BANK STATEMENT"  style="font-weight: bold;border-style:none;border-radius:0px;background-color:#ced1d1;padding:5px;cursor:pointer;" onmouseout="default_user()">
                        <img src="img/open_in_browser-26.png" width="25px">Excel file&nbsp;&nbsp;&nbsp;
                    </label>

                </div>

                <div class="input-group col-md-12" style='margin-bottom:4%;'>
                    <button class="btn pull-left btn-info" style="font-weight: bold;border-style:none;border-radius:0px;" type="button" onclick="uploadclicked_in()" id="upload" name="upload"><i class="glyphicon glyphicon-upload"></i> Upload</button>
                </div>

                {!! Form::close() !!}

            </div>





        </div>


    </div>
</div>

<script>
    var com;
    function default_user()
    {
        var default_user = '';
        //	document.getElementById('mainfile').value = document.getElementById('mainfiles').value

        document.getElementById('mainfile1s').value = document.getElementById('mainfiles2s').value
    }

    $("#com").change(function(){
        com = $("#com").val();
        if(com!="")
        {

            $("#dis_bu").remove();

            //  $("#dis_bank_act").remove();
            $.ajax({
                type:'GET',
                url:"loadBu/"+com,
                success:function(data)
                {
                    //  alert(data);
                    $("#bu_sel").html(data);
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
            $("#bu_sel").html('<input type="text" id="dis_bu" disabled placeholder="Business Unit" class="form-control">');
            $("#bank_act_sel").html('<input type="text" id="dis_bank_act" disabled placeholder="Bank Account List" class="form-control">');

        }
    });

    function uploadclicked_in(){
        var company1  = $("#com option:selected").text();
        var bunit1    = $("#bu option:selected").text();
        var bankacct1 = $("#bankact option:selected").text();
        var yeardate1 = $("#year option:selected").text();

        var bunit    = $("#bu").val();
        var bankacct = $("#bankact").val();
        var yeardate = $("#year").val();
        var company  = $("#com").val();
        //var f = document.getElementById('store').value;

        // if(f==""){
        // showMessageError("Please select store first!",'CPMS error!','');
        // }
        // else{
        if(yeardate == "" || yeardate==null)
        {
            alert("Please select Year");
        }
        else if(company == "")
        {
            alert("Please select Company");
        }
        else if(bunit == "")
        {
            alert("Please select Business Unit");
        }
        else if(bankacct == "")
        {
            alert("Please select Bank Account");
        }
        else
        {
//            showMessageConfirm("Are you sure you want to upload this all files? \n\t This Data will be upload for:\n Company: "+company+"\n Business Unit: "+bunit+"\n Bank Account: "+bankacct+"\n Year: "+yeardate,'BRS','',function(){
//                document.actionsend.submit();
//            });
           BootstrapDialog.show({
               title:'BRS',
               message:"Are you sure you want to upload this all files? \n\t This Data will be upload for:\n Company: "+company1+"\n Business Unit: "+bunit1+"\n Bank Account: "+bankacct1+"\n Year: "+yeardate1,
               buttons:[{
                   label:'Yes',
                   icon:'glyphicon glyphicon-thumbs-up',
                   cssClass:'btn btn-flat btn-success',
                   action:function(dialogRef)
                   {
                       document.actionsend.submit();
                   }
               }]
           });
        }

        // }



    }
</script>