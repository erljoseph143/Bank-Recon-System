<table id="sales-data" class="table table-condensed table-hover">
    <thead>
    <tr>
        <th>Description</th>
        <th>Sales Date</th>
        <th>DS Number</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
    @php
        $status= "";
    @endphp
    @foreach($cashLog as $key => $cash)
        @php
            $status = $cash->status_clerk;

        @endphp
        @if(preg_match("/$cash->cash_id/",$auth->cash_log))
            <tr>
                <td >
                    @if(preg_match('/AR#/',$cash->cashLog->description)>0)
                        {{$cash->cashLog->description. " " . $cash->ar_from ." - ". $cash->ar_to . " Others"}}
                    @else
                        {{$cash->cashLog->description}}
                    @endif
                </td>
                <td>
                    {{date('m/d/Y',strtotime($cash->sales_date))}}
                </td>
                <td style="width:35%">
                        <span class="text-muted">
                            {{$cash->ds_no}}
                        </span>
                </td>
                <td style="text-align: right;">
                    @if($cash->status_clerk!="posted")
                        <div id="_token" class="hidden" data-token="{{ csrf_token() }}"></div>
                        <a href="javascript:;"  class="data-amount" style="text-decoration: none;color:black" id="data1" data-type="text" data-pk="{{$cash->id}}" data-original-title="Enter username">
                            {{number_format($cash->amount_edited,2)}}
                        </a>
                    @else
                        {{number_format($cash->amount_edited,2)}}
                    @endif
                </td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>




<button class="btn btn-primary post-data" {{$status=="posted"? "disabled" :""}}>
    <i class="glyphicon glyphicon-send"></i>
    Post
</button>

<script>

    $.fn.editable.defaults.mode = 'inline';
    $.fn.editable.defaults.params = function (params) {
        params._token = $("#_token").data("token");
        return params;
    };


    $('.data-amount').editable({
        url: '{{url('treasury/saveEdit')}}',
        type: 'text',
        tpl: '<input type="text" class="amount form-control text-align-right" style="text-align:right;font-size:medium">'
    });

    $(document).on("focus", ".amount", function () {
        $(this).maskMoney();
    });

    $(".title-page").text('{{$content_title}}');

    $('.monthly-list-cash').click(function(){
        monthlyCash();
    });
    $('.daily-cash-list').click(function(){
        var date = $(this).data('date');
        dailyCash(date);
    });

$(".post-data").click(function(){
    BootstrapDialog.show({
        title:'Posting Sales',
        message:'Are you sure you want to post this data?',
        size:BootstrapDialog.SIZE_SMALL,
        dragable:false,
        closable:false,
        buttons:[
            {
                label:'Yes',
                icon:'glyphicon glyphicon-thumbs-up',
                cssClass:'btn btn-sm btn-success',
                action:function(dialog)
                {
                    $.ajax({
                        type:'get',
                        url:'{{url('liquidation/postingData/'.$date)}}',
                        success:function()
                        {
                           // $('.editable',allPages).editable('toggleDisabled');
                            $('.editable').editable('toggleDisabled');
                            $("#post-data").prop('disabled',true);
                            dialog.close();
                        }
                    });

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
</script>

