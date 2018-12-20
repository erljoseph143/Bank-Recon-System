<div class="col-md-7">
    <form action="{{url('dtr/DTRsaving')}}" id="dtr-upload" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="col-md-12 margin-top-10">
                <div class="input-group full-width">
                <span class="input-group-btn span-width">
                    <button class="btn form-btn" type="button">Company</button>
                </span>
                    {!! Form::select('com',[''=>'-----------------------Select Company------------------------']+$company,null,['class'=>'form-control','id'=>'com','required'=>'required']) !!}
                </div>
            </div>



            <div class="col-md-12 margin-top-10">
                <div class="input-group full-width">
                <span class="input-group-btn span-width">
                    <button class="btn form-btn" type="button">Business Unit</button>
                </span>
                    <div class="b-unit full-width">
                        <input type="text" name="bu" id="bu" disabled="disabled" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="col-md-12 margin-top-10">
                <div class="input-group full-width">
                <span class="input-group-btn span-width">
                    <button class="btn form-btn" type="button">Bank Account</button>
                </span>
                    <div class="b-acct full-width">
                        <input type="text" name="bankAcct" id="bankAcct" disabled="disabled" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="col-md-12 margin-top-10 bpi-type hidden">
                <div class="input-group full-width">
                <span class="input-group-btn span-width">
                    <button class="btn form-btn" type="button">BPI TYPE</button>
                </span>
                    <div class="full-width">
                        {!! Form::select('bpiType',[
                            ''=>'-------------------Select BPI type---------------------',
                            'BIZLINK'=>'BPI-BIZLINK',
                            'EXPLINK'=>'BPI-EXPLINK'
                        ],null,['class'=>'form-control','id'=>'bpi-type']) !!}
                    </div>
                </div>
            </div>


        <div class="form-group">
            <div class="col-md-12 margin-top-10">
                <label for="file" class="btn btn-default fileinput-button">
                    <i class="fa fa-plus"></i>
                    <span>
                    Add files
                </span>
                    <input id="file" type="file" name="dtr[]" multiple="multiple" style="display: none" required>
                </label>
                <label for="" class="num-file"></label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 margin-top-10">
                <button type="button" class="btn btn-default" onclick="DTRupload()">
                    <i class="fa fa-cloud-upload"></i>
                    Upload
                </button>
            </div>
        </div>

    </form>
</div>
<div class="clearfix"></div>

<style>
    .form-btn
    {
        background: #faebcc;
        color: green;
        border-bottom: 3px solid green;
        width: 100%;
    }
    .span-width
    {
        width: 150px;
    }

    .full-width
    {
        width: 100%;
    }
</style>

<script>
    var numFiles = 0;

    $("body").on("change", function(){
        numFiles = $("input[type=file]")[0].files.length;
        if(numFiles > 1)
        {
            $(".num-file").text(numFiles + ' files');
        }
        else
        {
            $(".num-file").text(numFiles + ' file');
        }

    });

    function DTRupload(){
        var numFiles = 0;
        var com      = $("#com").val();
        var bu       = $("#bu").val();
        var bankAcct = $('#bankAcct').val();
            numFiles = $("input[type=file]")[0].files.length;
        var bank     = $('#bankAcct option:selected').text();
        var bpiType  = $('#bpi-type').val();
        if(bank.match(/BPI/))
        {
            if(com!='' && bu!='' && bankAcct!='' && numFiles!=0 && bpiType!='')
            {
                BootstrapDialog.show({
                    title:'Uploading',
                    message:'Are you sure you want to upload?',
                    buttons:[
                        {
                            label:'Yes',
                            icon:'glyphicon glyphicon-thumbs-up',
                            cssClass:'btn btn-success',
                            action:function(dialog)
                            {
                                $("#dtr-upload").submit();
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
            else if(com=='')
            {
                BootstrapDialog.alert('Plese select Company!');
            }
            else if(bu =='')
            {
                BootstrapDialog.alert('Plese select Business Unit!');
            }
            else if(bpiType=='')
            {
                BootstrapDialog.alert('Plese select BPI type to be upload!');
            }
            else if(numFiles==0)
            {
                BootstrapDialog.alert('Plese select file to be upload!');
            }
            else
            {
                BootstrapDialog.alert('Plese select Bank Account!');
            }
        }
        else
        {
            if(com!='' && bu!='' && bankAcct!='' && numFiles!=0)
            {
                BootstrapDialog.show({
                    title:'Uploading',
                    message:'Are you sure you want to upload?',
                    buttons:[
                        {
                            label:'Yes',
                            icon:'glyphicon glyphicon-thumbs-up',
                            cssClass:'btn btn-success',
                            action:function(dialog)
                            {
                                $("#dtr-upload").submit();
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
            else if(com=='')
            {
                BootstrapDialog.alert('Plese select Company!');
            }
            else if(bu =='')
            {
                BootstrapDialog.alert('Plese select Business Unit!');
            }
            else if(numFiles==0)
            {
                BootstrapDialog.alert('Plese select file to be upload!');
            }
            else
            {
                BootstrapDialog.alert('Plese select Bank Account!');
            }
        }

    };


    var bu  = $('.b-unit').html();
    var bacct = $('.b-acct').html();
    $("#com").change(function(){

        var com = $(this).val();
        if(com != '')
        {
            $.ajax({
                type:'get',
                url:'{{url('dtr/bu')}}/'+com,
                success:function(data)
                {
                    $(".b-unit").html(data);
                }
            });
        }
        else
        {
            $(".b-unit").html(bu);
            $(".b-acct").html(bacct);
        }

    })
</script>