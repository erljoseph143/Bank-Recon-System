<form method="post" id="bs-adding">
    {{csrf_field()}}
    <input type="hidden" name="key" value="{{$key}}">
    <table class="table table-striped table-bordered" width="100%">
        <thead>
            <tr>
                <th>Bank Date</th>
                <th>Description</th>
                <th>Check No</th>
                <th>Debit Amount</th>
                <th>Credit Amount</th>
                <th>Bank Balance</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <input type="text" name="bank_date" class="form-control b-date" placeholder="mm/dd/YYYY">
                </td>
                <td>
                    <input type="text" class="form-control" name="des">
                </td>
                <td>
                    <input type="text" class="form-control" name="check_no">
                </td>
                <td>
                    <input type="text" class="form-control deb_amt" name="deb_amt">
                </td>
                <td>
                    <input type="text" class="form-control cred_amt" name="cred_amt">
                </td>
                <td>
                    <input type="text" class="form-control bal" name="balance">
                </td>
                <td>
                    <button class="btn btn-success btn-xs" type="submit">Save</button>
                    <button class="btn btn-danger btn-xs" type="reset">Reset</button>
                </td>
            </tr>
        </tbody>

    </table>
</form>

<script>
    $('.deb_amt').focusout(function(){
        setTimeout(function(){
            if($('.deb_amt').val()!='')
            {
                $(".cred_amt").prop('disabled',true);
            }
            else
            {
                $(".cred_amt").prop('disabled',false);
            }
        },100);
    });

    $('.cred_amt').focusout(function(){
        setTimeout(function(){
            if($('.cred_amt').val()!='')
            {
                $(".deb_amt").prop('disabled',true);
            }
            else
            {
                $(".deb_amt").prop('disabled',false);
            }
        },100);
    });

    $(document).on('focus','.deb_amt',function(){
        $(this).maskMoney();
    });
    $(document).on('focus','.cred_amt',function(){
        $(this).maskMoney();
    });
    $(document).on('focus','.bal',function(){
        $(this).maskMoney();
    });

    $("#bs-adding").submit(function(e){
        e.preventDefault();
        BootstrapDialog.show({
           title:'Confirmation',
           message:'Are you sure you want to save?',
           size:BootstrapDialog.SIZE_SMALL,
           buttons:[
               {
                   label:'Yes',
                   icon:'glyphicon glyphicon-thumbs-up',
                   cssClass:'btn btn-success btn-xs',
                   action:function(dialog)
                   {
                       $.ajax({
                           type:'post',
                           data:$("#bs-adding").serialize(),
                           url:'{{url('insertbs')}}',
                           success:function(data)
                           {
                               dialog.close();
                               BootstrapDialog.show({
                                   title:'result',
                                   message:data,
                               });
                           }

                       })
                   }
               },
               {
                   label:'No',
                   icon:'glyphicon glyphicon-thumbs-down',
                   cssClass:'btn btn-danger btn-xs',
                   action:function(dialog)
                   {
                       dialog.close();
                   }
               }
           ]
        });

    });

    var input = document.querySelectorAll('.b-date')[0];

    var dateInputMask = function dateInputMask(elm) {
        elm.addEventListener('keypress', function(e) {
            if(e.keyCode < 47 || e.keyCode > 57) {
                e.preventDefault();
            }

            var len = elm.value.length;

            // If we're at a particular place, let the user type the slash
            // i.e., 12/12/1212
            if(len !== 1 || len !== 3) {
                if(e.keyCode == 47) {
                    e.preventDefault();
                }
            }

            // If they don't add the slash, do it for them...
            if(len === 2) {
                elm.value += '/';
            }

            // If they don't add the slash, do it for them...
            if(len === 5) {
                elm.value += '/';
            }
        });
    };

    dateInputMask(input);
</script>
