                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <table id="requested-cash-approved" class="table table-striped table-bordered table-hover dataTable no-footer" >
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
                        <button id="{{$data->id}}" class="btn btn-success approve">
                            <i class="glyphicon glyphicon-thumbs-up"></i>

                        </button>
                        <button  id="{{$data->id}}" class="btn btn-danger inline disapprove">
                            <i class="glyphicon glyphicon-thumbs-down"></i>

                        </button>
                    </div>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    $("#requested-cash-approved").DataTable({'aaSorting':[]});
    $(".title-page").text('{{$content_title}}');
</script>