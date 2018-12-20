<table id="approve-data" class="table table-striped table-bordered table-hover dataTable no-footer" >
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
        <tr>
            <td>{{$data->tcpof_no}}</td>
            <td>{{date("m/d/Y",strtotime($data->pull_out_date))}}</td>
            <td style="text-align: right">{{number_format($data->amount,2)}}</td>
            <td>{{$data->amt_words}}</td>
            <td>{{$data->purposes->description}}</td>
            <td>{{$data->approve_by!=''?$data->approveby->firstname ." ". $data->approveby->lastname : ''}}</td>
            <td>{{$data->release_by!=''?$data->releaseby->firstname ." ". $data->releaseby->lastname : ''}}</td>
            <td>
                @if($data->cpo_status =='approve')
                    Approve <i id="{{$data->id}}" class="glyphicon glyphicon-print print" style="cursor:pointer;color:blue"></i>
                @else
                    <span class="label label-sm {{$data->cpo_status=='Pending'? 'label-warning' : 'label-success'}}">
                        {{$data->cpo_status}}
                    </span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    $("#approve-data").DataTable({"aaSorting": []});
    $(".print").click(function(){
        var id  = $(this).attr('id');
        $.ajax({
            type:'get',
            url:'{{url('cashpullout/PrintData')}}/'+id,
            success:function(data)
            {
                var p = window.open("",null,"resizable=no");
                p.document.write(data);
                p.print(p);
                p.close();
            }

        });
    });
</script>