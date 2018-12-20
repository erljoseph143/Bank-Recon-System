<table id="requested-cash-data" class="table table-striped table-bordered table-hover dataTable no-footer" >
    <thead>
    <tr>
        <th>Requested By</th>
        <th>TCPOF #</th>
        <th>Department/Section</th>
        <th>Date Requested</th>
        <th>Amount Requested</th>
        <th>Amount Approved</th>
        <th>Amount in Words</th>
        <th>Purpose</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cpo as $key => $data)
        <tr>
            <td>{{$data->user->firstname ." ". $data->user->lastname}}</td>
            <td>{{$data->tcpof_no}}</td>
            <td>{{$data->department->dep_name}}</td>
            <td>{{date("m/d/Y",strtotime($data->pull_out_date))}}</td>
            <td>{{number_format($data->amount,2)}}</td>
            <td>{{strtolower($data->cpo_status)=='approve'?number_format($data->amount_edited,2):''}}</td>
            <td>{{$data->amt_words}}</td>
            <td>{{$data->purposes->description}}</td>
            <td style="width:200px;" id="td{{$data->id}}">
                @if($data->cpo_status =='approve')
                    Approve
                    {{--<button id="{{$data->id}}" class="btn btn-primary print"><i class="glyphicon glyphicon-print"></i> Print</button>--}}
                @else
                    <div class="btn-group">
                        {{--<button id="{{$data->id}}" class="btn btn-success approve">--}}
                            <i class="glyphicon glyphicon-thumbs-up approve" style="font-size:x-large;cursor: pointer;" id="{{$data->id}}"></i>

                        {{--</button>--}}
                        <button  id="{{$data->id}}" class="btn btn-danger inline disapprove hidden">
                            <i class="glyphicon glyphicon-thumbs-down"></i>

                        </button>
                    </div>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@if(!isset($home))
    <script>
        $("#requested-cash-data").dataTable({
            "aaSorting": []
        });

        $(".title-page").text('{{$content_title}}');

        $(".approve").click(function(){
            var id  = $(this).attr('id');
            BootstrapDialog.show({
                title:'Are you sure you want to Approve?',
                message:$('<div></div>').load('{{url('cashpullout/approve')}}/'+id),
                type:BootstrapDialog.TYPE_INFO,
                size:BootstrapDialog.SIZE_WIDE,
                closable:false,
                buttons:[
                    {
                        label:'Yes',
                        icon:'glyphicon glyphicon-thumbs-up',
                        cssClass:'btn btn-success',
                        action:function(dialog)
                        {
                            $.ajax({
                                type:'post',
                                data:$('#approve-form').serialize(),
                                url:'cashpullout/approveRequest',
                                success:function(data)
                                {
//                               $("#td"+id).html('');
//                               $("#td"+id).html('Approve');
                                    dialog.close();
                                    location.reload();
                                }
                            });

                        }
                    },
                    {
                        label:'Close',
                        icon:'glyphicon glyphicon-remove',
                        cssClass:'btn btn-danger',
                        action:function(dialog)
                        {
                            dialog.close();
                        }
                    }
                ]

            });
        });
    </script>
@else
    @push('scripts')
    <script>
        $("#requested-cash-data").dataTable({
            "aaSorting": []
        });


        $(".approve").click(function(){
            var id  = $(this).attr('id');
            BootstrapDialog.show({
                title:'Are you sure you want to Approve?',
                message:$('<div></div>').load('{{url('cashpullout/approve')}}/'+id),
                type:BootstrapDialog.TYPE_INFO,
                size:BootstrapDialog.SIZE_WIDE,
                closable:false,
                buttons:[
                    {
                        label:'Yes',
                        icon:'glyphicon glyphicon-thumbs-up',
                        cssClass:'btn btn-success',
                        action:function(dialog)
                        {
                            $.ajax({
                                type:'post',
                                data:$('#approve-form').serialize(),
                                url:'cashpullout/approveRequest',
                                success:function(data)
                                {
//                               $("#td"+id).html('');
//                               $("#td"+id).html('Approve');
                                    dialog.close();
                                    location.reload();
                                }
                            });

                        }
                    },
                    {
                        label:'Close',
                        icon:'glyphicon glyphicon-remove',
                        cssClass:'btn btn-danger',
                        action:function(dialog)
                        {
                            dialog.close();
                        }
                    }
                ]

            });
        });
    </script>
    @endpush
@endif
