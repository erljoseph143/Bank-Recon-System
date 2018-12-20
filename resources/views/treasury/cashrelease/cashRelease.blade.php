<table id="cash-release" class="table table-bordered table-striped table-hover flip-content">
    <thead class="flip-content">
    <tr>
        <th>Requested By</th>
        <th>TCPOF #</th>
        <th>Department/Section</th>
        <th>Date Requested</th>
        {{--<th>Amount Requested</th>--}}
        <th>Approved Amount</th>
        <th>Amount in Words</th>
        <th>Approved By</th>
        <th>Purpose</th>
        <th>Released Date</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cpo as $data)
        <tr>
            <td>{{$data->user->firstname ." ". $data->user->lastname}}</td>
            <td>{{$data->tcpof_no}}</td>
            <td>{{$data->department->dep_name}}</td>
            <td>{{date("m/d/Y",strtotime($data->pull_out_date))}}</td>
            {{--<td>{{number_format($data->amount,2)}}</td>--}}
            <td id="td{{$data->id}}">{{number_format($data->amount_edited,2)}}</td>
            <td>{{$data->amt_words}}</td>
            <td>{{$data->approveby->firstname ." ". $data->approveby->lastname}}</td>
            <td>{{$data->purposes->description}}</td>
            <td>{{date("m/d/Y",strtotime($data->updated_at))}}</td>
            <td id="status{{$data->id}}">
                @if($data->release_status=='')
                    {{--<button class="btn btn-primary cash-release" id="{{$data->id}}">--}}
                    <i class="glyphicon glyphicon-tags cash-release font-blue-ebonyclay btn" id="{{$data->id}}"></i>

                    {{--</button>--}}
                @else
                    Released
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    $("#cash-release").dataTable({"aaSorting": []});

    $(".title-page").text('{{$content_title}}');

    $(".cash-release").click(function(){
        var id  = $(this).attr('id');
        var amt = $("#td"+id).text();
        BootstrapDialog.show({
            title:'Cash Releasing',
            message:'Are you sure you want to tag this amount ₱ '+ amt.trim() +' as release?',
            closable:false,
            buttons:[
                {
                    label:'Yes',
                    icon:'glyphicon glyphicon-thumbs-up',
                    cssClass:'btn btn-success btn-sm',
                    action:function(dialog)
                    {
                        $.ajax({
                            type:'get',
                            url:'{{url('treasury/release')}}/'+id,
                            success:function(data)
                            {
                                //location.reload();
                                $("#status"+id).html('Released');
                                dialog.close();
                            }
                        });

                    }
                },
                {
                    label:'No',
                    icon:'glyphicon glyphicon-thumbs-down',
                    cssClass:'btn btn-danger btn-sm',
                    action:function(dialog)
                    {
                        dialog.close();
                    }
                }
            ]
        });
    });
</script>
<style>
    /* width */
    ::-webkit-scrollbar {
        width: 5px;
        height: 8px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 5px grey;
        border-radius: 5px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #26a69a;
        border-radius: 5px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #26a69a;
    }
    .modal .modal-dialog {
        z-index: 10100;
    }
</style>