<div class="tabbable tabbable-custom tabbable-noborder tabbable-reversed color">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#all" id="all-tab" data-toggle="tab">
                All
            </a>
        </li>
        <li>
            <a href="#approve" id="approve-tab" data-toggle="tab">
                Approve
            </a>
        </li>
        <li>
            <a href="#release" id="release-tab" data-toggle="tab">
                Released
            </a>
        </li>
        <li>
            <a href="#pending" id="pending-tab" data-toggle="tab">
                Pending
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="all">
            <table id="requested-data" class="table table-striped table-bordered table-hover dataTable no-footer" >
                <thead>
                <tr>
                    {{--<th>Requested By</th>--}}
                    <th>TCPOF #</th>
                    {{--<th>Department/Section</th>--}}
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
                        {{--<td>{{$data->user->firstname ." ". $data->user->lastname}}</td>--}}
                        <td>{{$data->tcpof_no}}</td>
                        {{--<td>{{$data->department->dep_name}}</td>--}}
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
        </div>

        <div class="tab-pane active" id="approve">
            <div class="approve-body">

            </div>
        </div>

        <div class="tab-pane active" id="release">
            <div class="release-body">

            </div>
        </div>

        <div class="tab-pane active" id="pending">
            <div class="pending-body">

            </div>
        </div>
    </div>
</div>



<script>
    $("#requested-data").DataTable({
        "aaSorting": []
    });

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

    $("#approve-tab").click(function(){
        $(".approve-body").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('cashpullout/viewApprove')}}',
            success:function(data)
            {
                $(".page-breadcrumb").html('');
                $(".page-breadcrumb").html('<li>'+
                    '<i class="fa fa-home"></i>'+
                    '<a href="#" class="monthly-for-monthly">Home</a>'+
                    '<i class="fa fa-angle-right"></i>'+
                    '</li>');

                $(".approve-body").html(data);
            }
        })
    });

    $("#release-tab").click(function(){
        $(".approve-body").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('cashpullout/viewRelease')}}',
            success:function(data)
            {
                $(".page-breadcrumb").html('');
                $(".page-breadcrumb").html('<li>'+
                    '<i class="fa fa-home"></i>'+
                    '<a href="#" class="monthly-for-monthly">Home</a>'+
                    '<i class="fa fa-angle-right"></i>'+
                    '</li>');

                $(".release-body").html(data);
            }
        })
    });

    $("#pending-tab").click(function(){
        $(".approve-body").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('cashpullout/viewPending')}}',
            success:function(data)
            {
                $(".page-breadcrumb").html('');
                $(".page-breadcrumb").html('<li>'+
                    '<i class="fa fa-home"></i>'+
                    '<a href="#" class="monthly-for-monthly">Home</a>'+
                    '<i class="fa fa-angle-right"></i>'+
                    '</li>');

                $(".pending-body").html(data);
            }
        })
    });


</script>

<style>
    .tabbable-custom.tabbable-noborder > .nav-tabs > li > a {
        border: 0;
        background: #faebcc;
        color: green;
        //margin-left: -3%;
        border-bottom:2px solid green;
    }

    .tabbable-custom.tabbable-noborder > .nav-tabs > li.active > a {
        border: 0;
        background: white;
        color: green;
        //margin-left: -3%;
    }

    .color{
        background: #faebcc;
    }
</style>