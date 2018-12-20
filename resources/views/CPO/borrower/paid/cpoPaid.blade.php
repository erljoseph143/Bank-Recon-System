<table id="cpo-paid-data" class="table table-striped table-bordered table-hover dataTable no-footer" >
    <thead>
    <tr>
        <th>TCPOF #</th>
        <th>Date Requested</th>
        <th>Amount</th>
        <th>Amount in Words</th>
        <th>Purpose</th>
        <th>Approved By</th>
        <th>Released By</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cpo as $key => $data)
        @if(count($data->ledger) > 0)
            <tr>
                <td>{{$data->tcpof_no}}</td>
                <td>{{date("m/d/Y",strtotime($data->pull_out_date))}}</td>
                <td style="text-align: right">{{number_format($data->amount,2)}}</td>
                <td>{{$data->amt_words}}</td>
                <td>{{$data->purposes->description}}</td>
                <td>{{$data->approve_by!=''?$data->approveby->firstname ." ". $data->approveby->lastname : ''}}</td>
                <td>{{$data->release_by!=''?$data->releaseby->firstname ." ". $data->releaseby->lastname : ''}}</td>
                <td>
                    {{$data->status_paid=='Paid'?'Paid':'Unpaid'}} <i class="glyphicon glyphicon-eye-open ledger-view" id="{{$data->id}}" style="cursor: pointer;color:blue"></i>
                </td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>

<script>
    $("#cpo-paid-data").DataTable({"aaSorting": []});
    $(".ledger-view").click(function(){
        var id = $(this).attr('id');
        BootstrapDialog.show({
            title:'Cash Pull Out Payment Ledger',
            message:$("<div></div>").load('{{url('cashpullout/viewledger')}}/'+id),
            size:BootstrapDialog.SIZE_WIDE
        });
    });
</script>