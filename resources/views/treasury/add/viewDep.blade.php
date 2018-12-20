

<div class="row">
    <div class="form-group">
        <label class="control-label col-md-2">Deposit Date</label>
        <div class="col-md-4">
            <div class="input-group date date-picker" data-date-format="mm/dd/yyyy" data-date-start-date="-{{ $format }}d">
                <input type="text" id="dep-date" class="form-control" readonly name="datepicker" >
                <span class="input-group-btn">
                    <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                </span>
            </div>

        </div>
    </div>
    <br>
    <br>
</div>

<div class="row">
    {{--    TABLE DATA      --}}

    <div class="col-md-6">
        <table class="table table-condensed table-hover" id="viewDep-checks">
            <thead>
            <tr>
                <th>Description</th>
                <th>Trans Type</th>
                <th>Amount</th>

            </tr>
            </thead>
            <tbody>
            {{--{{dd(session()->get('check_data_receive'))}}--}}
            @php
                session()->forget('check-data');
                session()->forget('cpo-payment');
                session(['check-data'=>$checkClass]);
                session(['cpo-payment'=>$cpolg]);
            @endphp
            @foreach($checkClass as $check)
                <tr>
                    <td>
                        <a href="#" class="check-class" data-class="{{$check->check_class}}">
                            {{$check->check_class}}
                        </a>

                    </td>
                    <td>{{$check->check_from}}</td>
                    <td style="text-align:right">{{number_format($check->check_amt_total,2)}}</td>
                </tr>
            @endforeach
            @foreach($cpolg as $key => $cpl)
                <tr>
                    <td>CPO Payment for {{date("m/d/Y",strtotime($cpl->cpo_date))}}</td>
                    <td>{{$cpl->check_no}}</td>
                    <td style="text-align:right">{{number_format($cpl->check_amount,2)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-condensed table-hover" id="viewDep-cash">
            <thead>
            <tr>
                <th >Type</th>
                <th>Amount</th>
                {{--<th>Action</th>--}}
            </tr>
            </thead>
            <tbody id="tbody-adj">
            <tr>
                <td>Cash For Deposit</td>
                <td id="cash-dep" class="adj" style="text-align:right"></td>
                {{--<td></td>--}}
            </tr>
            @php
                $dataAdj = Array();
                $dataAdj[] = [3,'Cash For Deposit',$cashForDep];

            @endphp
            @foreach($adjustment as $key => $adj)
                @php
                    $adjust_entry = ['Due Checks'];
                    $dataAdj[] = [$adj[0],$adj[1],$adj[2]];
                    if(!in_array(trim($adj[1]),$adjust_entry))
                    {
                        $hidden = "hidden";
                        $color  = "red";
                    }
                    else
                    {
                        $hidden = "";
                        $color  = "black";
                    }
                @endphp
                <tr style="color:{{$color}}">
                    <td class="des-data" id="des-data{{$adj[0]}}">{{$adj[1]}}</td>
                    <td class="amt-data adj" id="amt{{$adj[0]}}" style="text-align:right">{{number_format($adj[2],2)}}</td>
                    {{--<td><label class="btn btn-sm btn-default {{$hidden}}  edit-adj" data-id="{{$adj[0]}}"><i class="glyphicon glyphicon-edit"></i> Edit</label></td>--}}
                </tr>
            @endforeach
            @php

                session()->forget('adjustment-data');
                session(['adjustment-data'=>$dataAdj]);
            @endphp
            </tbody>
        </table>
    </div>



</div>

<div class="row">
    {{--  TOTAL PER TABLE DATA   --}}

    <div class="col-md-6">
        <div class="caption">
            <div class="col-sm-4">
                <label class="caption-subject font-green-sharp bold uppercase" for="">Total:</label>
            </div>
            <div class="col-sm-8">
                <div class="input-group pull-right">
                    <label id="check-total" for="">{{number_format($totalChecks+$cpolgsum,2)}}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="caption">
            <div class="col-sm-4">
                <label class="caption-subject font-green-sharp bold uppercase" for="">Totals:</label>
            </div>
            <div class="col-sm-8">
                <div class="input-group pull-right">
                    <label class="total-adj" for="">0</label>
                </div>
            </div>
        </div>
    </div>
</div>

{{--       FOR CASH DEPOSITING            --}}

<div class="row">
    <div class="col-md-12">
        <table id="sales-data" class="table table-bordered table-striped" style="font-size: medium;">
            <thead>
            <tr>
                <th>Description</th>
                <th>Sales Date</th>
                <th>DS Number</th>
                <th>Amount</th>
                <th>Final Amount</th>
            </tr>
            </thead>
            <tbody>
            @php
                session()->forget('sales-data');
                $arrayLogs = Array();
                $status= "";
                $total = 0;
            @endphp
            @foreach($cashLog as $key => $cash)
                @php
                    $total_amt =0;
                        $status = $cash->status_treasury;
                        if(preg_match('/AR#/',$cash->cashLog->description)>0):
                         $description = $cash->cashLog->description. " " . $cash->ar_from ." - ". $cash->ar_to . " Others";
                        else:
                         $description = $cash->cashLog->description;
                        endif;
                @endphp

                @if(preg_match('/supermarket/',strtolower($cash->cashLog->description))>0)
                    <tr>
                        <td >
                            {{$description}}
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
                            @php
                                if(count($adjustment)>0):
                                    foreach($adjustment as $key => $adj)
                                    {
                                        if(strtolower($adj[1]) !="due checks"):
                                            $total_amt +=$adj[2];
                                        endif;
                                    }
                                    $total = $cash->amount_edited-$total_amt;
                                else:
                                    $total += $cash->amount_edited;
                                endif;


                            $arrayLogs[] =  (object)[
                                                'description' => $description,
                                                'sales_date'  => $cash->sales_date,
                                                'ds_no'       => $cash->ds_no,
                                                'amount_sm'   => $cash->amount_edited,
                                                'final_amount'=> ''

                                            ];
                            @endphp
                            <a href="javascript:;" style="text-decoration: none;color:#000000"  class="supermarket sup-ds" id="data1" data-type="text" data-pk="{{$cash->id}}" data-original-title="">
                                {{number_format($cash->amount_edited,2)}}
                            </a>

                        </td>
                        <td >

                        </td>

                    </tr>

                    @if(count($adjustment)>0)
                        @php
                            $maxKey = max(array_keys($adjustment));
                        @endphp
                        @foreach($adjustment as $key => $adj)
                            @if(strtolower($adj[1]) !="due checks")
                                <tr>
                                    <td colspan="3" style="text-align: right;"  id="des{{$adj[0]}}" >{{$adj[1]}}</td>
                                    <td style="text-align:right;color:red" class="amt-data adj sm-details" id="sm-details{{$adj[0]}}">{{number_format($adj[2],2)}}</td>
                                    @if($key==count($adjustment)-1 and $key==$maxKey)
                                        <td class="final-SM  cash-all" style="text-align: right">
                                            {{number_format($total,2)}}
                                            @php
                                                $arrayLogs[] =  (object)[
                                                                   'description' => $adj[1],
                                                                   'sales_date'  => '',
                                                                   'ds_no'       => '',
                                                                   'amount_sm'   => -$adj[2],
                                                                   'final_amount'=> $total

                                                               ];
                                            @endphp
                                        </td>
                                    @elseif($key==count($adjustment)-2 and strtolower($adjustment[$maxKey][0])=="due checks")
                                        <td class="final-SM  cash-all" style="text-align: right">
                                            {{number_format($total,2)}}
                                            @php
                                                $arrayLogs[] =  (object)[
                                                                   'description' => $adj[1],
                                                                   'sales_date'  => '',
                                                                   'ds_no'       => '',
                                                                   'amount_sm'   => -$adj[2],
                                                                   'final_amount'=> $total

                                                               ];
                                            @endphp
                                        </td>
                                    @else
                                        <td class="final-SM"></td>
                                        @php
                                            $arrayLogs[] =  (object)[
                                                               'description' => $adj[1],
                                                               'sales_date'  => '',
                                                               'ds_no'       => '',
                                                               'amount_sm'   => -$adj[2],
                                                               'final_amount'=> ''

                                                           ];
                                        @endphp
                                    @endif


                                </tr>
                            @endif
                        @endforeach
                    @endif
                @else
                    <tr>
                        <td >
                            {{$description}}
                        </td>
                        <td>
                            {{date('m/d/Y',strtotime($cash->sales_date))}}
                        </td>
                        <td style="width:35%">
                            <span class="text-muted">
                                {{$cash->ds_no}}
                            </span>
                        </td>
                        <td>

                        </td>
                        <td style="text-align: right;">
                            @php
                                $total += $cash->amount_edited;
                            @endphp
                            @if($cash->status_treasury!="posted")
                                <div id="_token" class="hidden" data-token="{{ csrf_token() }}"></div>
                                <a href="javascript:;" style="text-decoration: none;color:#000000"  class="data-amount cash-all" id="data1" data-type="text" data-pk="{{$cash->id}}" data-original-title="">
                                    {{number_format($cash->amount_edited,2)}}
                                </a>
                                @php
                                    $arrayLogs[] =  (object)[
                                                       'description' => $description,
                                                       'sales_date'  => $cash->sales_date,
                                                       'ds_no'       => $cash->ds_no,
                                                       'amount_sm'   => '',
                                                       'final_amount'=> $cash->amount_edited

                                                   ];
                                @endphp
                            @else
                                {{ number_format($cash->amount_edited,2)}}
                                @php
                                    $arrayLogs[] =  (object)[
                                                       'description' => $description,
                                                       'sales_date'  => $cash->sales_date,
                                                       'ds_no'       => $cash->ds_no,
                                                       'amount_sm'   => '',
                                                       'final_amount'=> $cash->amount_edited

                                                   ];
                                @endphp
                            @endif
                        </td>
                    </tr>

                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-10">
        <label class="pull-right caption-subject font-green-sharp bold uppercase" for="">Total:</label>
    </div>
    <div class="col-md-2">
        <div class="input-icon right has-success">
            <i class="fa fa-check tooltips" data-original-title="Total Cash Amount"></i>
            <input type="text" readonly class="form-control cash-all-total" id="cash-total" style="text-align:right" value="{{number_format($total,2)}}">
        </div>

    </div>
</div>


<div class="form-actions">
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class=" col-md-9">
                    <button  data-date="{{$date}}" id="post-data" data-url="{{url("treasury/posting/$date")}}" class="btn green  "><i class="glyphicon glyphicon-send"></i> Post</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
        </div>
    </div>
</div>

@php
    session(['sales-data'=>$arrayLogs]);
@endphp

{{--{{dd(session()->get('sales-data'))}}--}}



<script>
    $(".monthly-for-monthly").click(function(){
        cashForDep();
    });
    $(".monthly").click(function(){
        var date = $(this).data('date');
        $("#content").html("<div><img src='ajax.gif'><small class='text-danger'>please wait...</small></div>");
        $.ajax({
            type:'get',
            url:'{{url('treasury/dailyDep')}}/'+date,
            success:function(data)
            {
                $(".page-breadcrumb").html('');
                $(".page-breadcrumb").html('<li> <i class="fa fa-home"></i> <a href="home">Home</a> <i class="fa fa-angle-right"></i> </li>');
                $("#content").fadeOut('slow');
                $("#content").html('');
                $("#content").fadeIn('slow',function(){
                    $(this).html(data);
                });

            }
        })
    });

    $.fn.editable.defaults.mode = 'inline';
    $.fn.editable.defaults.params = function (params) {
        params._token = $("#_token").data("token");
        return params;
    };


    $('#content').find('.data-amount').editable({
        url: '{{url('treasury/saveEdit')}}',
        type: 'text',
        tpl: '<input type="text" class="amount form-control input-lg text-align-right" style="text-align:right;font-size:medium">',
        success:function(data)
        {
            setTimeout(function(){
                cash_total = 0;
                $('.cash-all').each(function(){
                    if($(this).text().trim()!='')
                    {
                        cash_total +=parseFloat($(this).text().trim().replace(/,/g,''));
                       // console.log(parseFloat($(this).text().trim().replace(/,/g,'')));
                    }

                });

                $(".cash-all-total").val(cash_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g,","));

                $('#cash-dep').text(cash_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g,","));

                //Total Adjustment
                var total_adj = 0;
                $("#tbody-adj .adj").each(function(){
                    total_adj +=parseFloat(parseFloat($(this).text().replace(/,/g,'').trim()).toFixed(2));
                });
                console.log(total_adj);
                total_adj = total_adj.toFixed(2);
                $(".total-adj").text(total_adj.toString().replace(/\B(?=(\d{3})+(?!\d))/g,','));
            },1000);
        }
    });

    $(document).on("focus", ".amount", function () {
        $(this).maskMoney();
    });
</script>

<script>


    $(document).on('keyup', '.ds-number', function (e) {
        if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')
    });

    $(document).on("focus", ".ds_no", function () {
        //$(this).maskMoney();
    });

    $('#bankact').on('change',function(){
        console.log($(this).children("option").filter(":selected").text());
        var val = $(this).val();
        if(val!="")
        {
            $(this).removeClass('blink-border');
        }
        else
        {
            $(this).addClass('blink-border');
        }
    })

    $(document).ready(function(){
        $('#cash-dep').text($('#cash-total').val());

        // $(".total-adj").text($('#cash-total').val());
        //Total Adjustment
        var total_adj = 0;
        $("#tbody-adj .adj").each(function(){
            total_adj +=parseFloat($(this).text().replace(/,/g,'').trim());
        });
        total_adj = total_adj.toFixed(2);
        $(".total-adj").text(total_adj.toString().replace(/\B(?=(\d{3})+(?!\d))/g,','));
    });

    //        var sm     = $(".supermarket").text().replace(/,/g,'').trim();
    //        $(".adjustment-amount").keyup(function(){
    //            var amount = $(this).val().replace(/,/g,'');
    //
    //            var diff   = sm - amount;
    //            console.log(diff);
    //            $('.supermarket').text(diff.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    //        });




</script>


<script>
    $("#post-data").click(function(){
        var url = $(this).data('url');
        var depDate = $('#dep-date').val();
        var newDep  = depDate.split('/');

        var newDepDate = newDep[2]+'-'+newDep[0]+'-'+newDep[1];

        if(depDate!="")
        {
            BootstrapDialog.show({
                title:'Posting Logs',
                //message:$('<div id="acccount"></div>').load(url),
                message:$('<div id="account">Are you sure you want to post?</div>'),
                size:BootstrapDialog.SIZE_SMALL,
                closable:false,
                buttons:[
                    {
                        label:' POST NOW',
                        icon:'glyphicon glyphicon-send',
                        cssClass:'btn btn-sm btn-success',
                        action:function(dialog)
                        {
                            // $("#account").text('Posting '+url);
                            $.ajax({
                                type:'get',
                                url:url+'/'+newDepDate,
                                success:function(data)
                                {
                                    console.log(data);
                                    //window.open('{{url("logPdf/$date")}}/'+newDepDate,'_blank');
                                    BootstrapDialog.show({
                                        title:'Log Reports',
                                        message:$('<div><a href="{{url("treasury/logPdf/$date")}}/'+newDepDate+'" class="btn col-md-12  btn-danger">Print Log in PDF </a><div class="clearfix"></div></div>'),
                                        size:BootstrapDialog.SIZE_SMALL,
                                        closable:false,
                                    });
                                    dialog.close();
                                }
                            });
                        }
                    },
                    {
                        label:' Cancel',
                        icon:'glyphicon glyphicon-remove',
                        cssClass:'btn btn-sm btn-danger',
                        action:function(dialog)
                        {
                            dialog.close();
                        }
                    }
                ]
            });
        }
        else
        {
            var dialog = BootstrapDialog.show({
                title:'Warning',
                message:'Deposit Date is Required',
                size:BootstrapDialog.SIZE_SMALL,
                closable:false,
                buttons:[
                    {
                        label:' Ok',
                        icon:'glyphicon glyphicon-check',
                        cssClass:'btn btn-xs btn-default',
                        action:function(dialog)
                        {
                            $("#dep-date").focus();
                            $("#dep-date").addClass('blink-border');
                            dialog.close();
                        }
                    }
                ]
            });
            dialog.getModalHeader().css('background-color', '#FF7201');
            dialog.getModalHeader().css('color', 'white');
            dialog.getModalHeader().text('Warning');
        }

    });

    $(document).on('focus','#dep-date',function(){
        $("#dep-date").removeClass('blink-border');
    })

    $(".title-page").text('Cash Received for {{date("F j, Y",strtotime($date))}}')


    $(".check-class").click(function(){
        var checkClass = $(this).data('class');
        BootstrapDialog.show({
            title:'Check Details',
            message:$('<div></div>').load('{{url('treasury/checkDetails')}}/'+checkClass+'/{{$date}}'),
            size:BootstrapDialog.SIZE_WIDE,
            closable:false,
            buttons:[
                {
                    label:'Close',
                    icon:'glyphicon glyphicon-remove',
                    cssClass:'btn btn-default',
                    action:function(dialog)
                    {
                        dialog.close();
                    }
                }
            ]
        });
    });



</script>
<script type="text/javascript" src="{{url('logbook/metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script>
    $('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        autoclose: true
    });

</script>