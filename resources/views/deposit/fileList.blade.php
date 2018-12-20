<a href="{{url('deposit/deplist')}}" class="btn btn-warning">
    <i class="glyphicon glyphicon-arrow-left"></i>
    Back
</a>
<table id="file-dep-list" class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Bank Name</th>
        <th>Account No</th>
        <th>Excel File</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($flist as $key => $excel)

        <tr>
            <td>{{$bAcct}}</td>
            <td>{{$bNum}}</td>
            <td>
                <img src="{{url('Excel-icon.png')}}" height="20px" width="20px">
                {{$file[$key]}}
            </td>
            <td>
                <a href='#' file="{{base64_encode($excel)}}" class="view-excel" style="display:table-cell;margin:5px">
                    <label class='col-sm-12 btn btn-xs btn-default btn-flat'>
                        <i class='glyphicon glyphicon-zoom-in'></i> view
                    </label>
                </a>
                <form action="{{url('deposit/saveExcel')}}" method="POST" class="excel-data-form" id="excel-data-form{{$key}}"  enctype="multipart/form-data" style="display:table-cell;margin:5px">
                    <input type="hidden" name="path" value="{{$excel}}">
                    <input type="hidden" name="filename" value="{{$file[$key]}}">
                    <input type="hidden" name="bankAct" value="{{$bankID}}">
                    {{csrf_field()}}
                    <button type="submit" class='col-sm-12 btn btn-xs btn-default btn-flat' >
                        <i class='glyphicon glyphicon-arrow-right'></i> Match To BS
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="col-md-12 data"></div>

<script>

    $("#file-dep-list").DataTable();

    $(document).on("click",".view-excel",function(){
        var file = $(this).attr('file');
        BootstrapDialog.show({
            title:"Deposit Excel Viewing",
            message:function(dialog) {
                var $message = $("<div><img src='{{url('ajax.gif')}}'><small class='text-danger'>please wait...</small></div>");
                var pageToLoad = dialog.getData('pageToLoad');
                setTimeout(function(){
                    $message.load(pageToLoad);
                },1000);
                return $message;
            },
            data: {
                'pageToLoad': '{{url('deposit/viewExcel/')}}/'+file,
            },
            onhidden: function(dialogRef){

            },
            type:BootstrapDialog.TYPE_SUCCES,
            size:BootstrapDialog.SIZE_WIDE,
            buttons:[
                {
                    label:'Close',
                    icon:'glyphicon glyphicon-remove',
                    cssClass:'btn btn-success',
                    action:function(dialog)
                    {
                        dialog.close();
                    }
                }
            ]

        });
    });

    $(".excel-data-form").submit(function(e){
        var submitForm = $(this).attr('id');
        var bs   = "";
      //  console.log(submitForm);
        e.preventDefault();
        $.ajax({
            type:'post',
            data:$("#"+submitForm).serialize(),
            url:'{{url('deposit/countBS')}}',
            success:function(data)
            {
                //console.log(data);
              bs = data.trim();
              if(!isNaN(parseInt(bs)))
              {
                  $("#"+submitForm).unbind('submit').submit();
                  //$("#"+submitForm).submit();
              }
              else
              {
                  BootstrapDialog.show({
                      title:'Warning',
                      message:'No Bank Statement uploaded for this month of '+data+' Please contact Record Management Staff for assistant',
                      size:BootstrapDialog.SIZE_WIDE,
                      type:BootstrapDialog.TYPE_DANGER,
                      closable:false,
                      buttons:[
                          {
                              label:'Close',
                              icon:'glyphicon glyhpicon-remove',
                              cssClass:'btn btn-default',
                              action:function(dialog)
                              {
                                  dialog.close();
                              }
                          }
                      ]
                  });
              }
            }
        })
    })
</script>