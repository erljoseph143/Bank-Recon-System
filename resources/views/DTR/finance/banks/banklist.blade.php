<table id="dtr" class="table table-condensed table-hover">
    <thead>
        <tr>
            <td>Bank</td>
            <td>Account No.</td>
            <td>Account Name</td>
            <td>Business Unit</td>
            <td>Lastest Date Uploaded</td>
            <td>Current Balance</td>
            <td>Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($allbank as $key => $bank)
            <tr>
                <td>{{$bank->bank}}</td>
                <td>{{$bank->accountno}}</td>
                <td>{{$bank->accountname}}</td>
                <td>{{$bank->businessunit->bname}}</td>
                <td id="date{{$bank->id}}">{{$dtrdata[$key][0]!=''?date("m/d/Y",strtotime($dtrdata[$key][0])):'No Data Found'}}</td>
                <td id="balance{{$bank->id}}">{{$dtrdata[$key][1]!=''?number_format($dtrdata[$key][1],2):'No Data Found'}}</td>
                <td>
                    <button class="btn btn-info upload-bank"
                            data-company="{{$bank->company_code}}"
                            data-bu="{{$bank->buid}}"
                            data-bank-acct="{{$bank->id}}"
                            data-bank="{{$bank->bank}}"
                            data-for="{{$bank->bank." - ".$bank->accountno." - ".$bank->accountname}}"
                            data-bankid="{{$bank->id}}">

                        <i class="glyphicon glyphicon-upload"></i>

                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="hidden table-upload-data">

</div>

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
    $("#dtr").DataTable();
    var modal;
    var bankId ="";
    $(".upload-bank").click(function(){
        var formdata = $(".table-upload-data").html();
        var bank     = $(this).data('bank');
        var bankacct = $(this).data('bank-acct');
        var com      = $(this).data('company');
        var bu       = $(this).data('bu');
        var datafor  = $(this).data('for');
        bankId   = $(this).data('bankid');


        setTimeout(function(){
            if(bank.match(/BPI/))
            {
                $(".bpi-type").removeClass("hidden");
            }
            else
            {
                $(".bpi-type").addClass("hidden");
            }
            $(".data-for").text(datafor);

        },500);


       modal = BootstrapDialog.show({
            title:'Upload Daily Transaction',
            message:$('<div class="form-data"></div>').load('{{url('dtr/form')}}'+'/'+bank+'/'+bankacct+'/'+com+'/'+bu),
            size:BootstrapDialog.SIZE_WIDE,
            closable:false,
            buttons:[
                {
                    label:'close',
                    icon:'glyphicon glyphicon-remove',
                    cssClass:'btn btn-default',
                    action:function(dialog)
                    {
                        dialog.close();
                    }
                }
            ]
        });
    });


</script>