<table id="cpo-data" class="table table-striped table-bordered table-hover dataTable no-footer" >
    <thead>
    <tr>
        <th>Requested By</th>
        <th>TCPOF #</th>
        <th>Department/Section</th>
        <th>Date Requested</th>
        <th>Amount Approved</th>
        <th>Amount in Words</th>
        <th>Purpose</th>
        <th>Approved By</th>
        <th>Released By</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cpo as $key => $data)
        <tr style="color: {{$data->status_paid!='Paid'?'red':'none'}}">
            <td>{{$data->user->firstname ." ". $data->user->lastname}}</td>
            <td>{{$data->tcpof_no}}</td>
            <td>{{$data->department->dep_name}}</td>
            <td>{{date("m/d/Y",strtotime($data->pull_out_date))}}</td>
            <td>{{number_format($data->amount_edited,2)}}</td>
            <td>{{$data->amt_words}}</td>
            <td>{{$data->purposes->description}}</td>
            <td>{{$data->approveby->firstname ." ". $data->approveby->lastname}}</td>
            <td>{{$data->releaseby->firstname ." ". $data->releaseby->lastname}}</td>
            <td>
                @if($data->status_paid=='Paid')
                    Paid
                @else
                    <i class="glyphicon glyphicon-tasks font-blue-chambray btn payment" id="{{$data->id}}"></i>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@if(!isset($home))
    <script>
        $("#cpo-data").DataTable({'aaSorting':[]});

        var mbox = {};
        $(".payment").click(function(){
            var id  = $(this).attr('id');
            mbox = BootstrapDialog.show({
                title:'Payment',
                size:BootstrapDialog.SIZE_DEFAULT,
                message:$('<div></div>').load('{{url('cashpullout/payment')}}/'+id),
                size:BootstrapDialog.SIZE_WIDE,
                closable:false

            });

           // mbox.getModalHeader().hide();
        });
    </script>
@else
    @push('scripts')
        <script>
            $("#cpo-data").DataTable({'aaSorting':[]});

            var mbox = {};
            $(".payment").click(function(){
                var id  = $(this).attr('id');
                mbox = BootstrapDialog.show({
                    title:'Payment',
                    size:BootstrapDialog.SIZE_DEFAULT,
                    message:$('<div></div>').load('{{url('cashpullout/payment')}}/'+id),
                    size:BootstrapDialog.SIZE_WIDE,
                    closable:false

                });

                //mbox.getModalHeader().hide();


            });
        </script>
    @endpush
@endif